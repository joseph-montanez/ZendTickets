<?php
class TicketController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
    public function listAction()
    {
        $ticketModel = new Default_Model_Ticket();
        $tickets = $ticketModel->fetchAll();

        $this->view->tickets = $tickets;
    }
    
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $ticket  = new Default_Model_Ticket();
        
        // Get Ticket Id
        $id = $request->getParam("id");
        
        $ticket->delete($id);
        
        return $this->_helper->redirector('list');
    }
    
    public function attachmentAction()
    {
        $request    = $this->getRequest();
        $attachment = new Default_Model_TicketAttachment();
        
        // Get Ticket Id
        $id = $request->getParam("id");
        $attachment->find($id);
        
        header('Content-type: ' . $attachment->contentType);
        echo $attachment->content;
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function indexAction()
    {
        // Send confirmation
        $smtp_config = array(
            'auth'     => 'login',
            'username' => 'tickets@xxxxxx.com',
            'password' => 'xxxxx',
            'port'     => 587,
            'ssl'      => 'TLS'
        );
        $pop3_config = array(
            'host'     => 'pop.gmail.com',
            'port'     => 995,
            'ssl'      => 'SSL',
            'user'     => 'tickets@xxxxx.com',
            'password' => 'xxxxxx'
        );
        
        $mail = new Zend_Mail_Storage_Pop3($pop3_config);

        echo $mail->countMessages() . " messages found\n";
        foreach ($mail as $i => $message) {
            $message = $this->parseMessage($mail, $message, $i);
            $mail->removeMessage($i);
            
            if (!stristr($message['message']['subject'], 'ticket #')) {
                // Add email as a ticket
                $ticketModel = new Default_Model_Ticket($message['message']);
                $ticketModel->save();
                $ticketId = $ticketModel->getMapper()->getDbTable()->getAdapter()->lastInsertId();
                
                $replyId = 0;
                

                $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $smtp_config);
                // Send a plain text email
                $sendmail = new Zend_Mail();
                $sendmail->setBodyText('Thank you for your submission. We will process you ticket shortly.');
                $sendmail->setFrom('tickets@xxxxxx.com', 'Zend Tickets');
                $sendmail->addTo($message['message']['fromEmail']);
                $sendmail->setSubject('Ticket #' . $ticketId);
                $sendmail->send($transport);
            } else {
                // Get ticket it from subject
                $ticketId = explode('ticket #', strtolower($message['message']['subject']));
                $ticketId = explode(' ', $ticketId[1]);
                $ticketId = array_shift($ticketId);
                
                $message['message']['ticketId'] = $ticketId;
                
                // Add email as a reply
                $replyModel = new Default_Model_TicketReply($message['message']);
                $replyModel->save();
                $replyId = $replyModel->getMapper()->getDbTable()->getAdapter()->lastInsertId();
                
            }
            
            
            foreach ($message['attachments'] as &$attachment) {
                if (empty($attachment['filename'])) {
                   // This is probably a text or html alternative.
                   $attachment['filename'] = stristr($attachment['contentType'], 'html') ? 'html-version.html' : 'text-version.txt';
                }
                $attachment['ticketId'] = $ticketId;
                // TODO: reply not being set?
                $attachment['replyId'] = $replyId;
                $ticketAttachmentModel = new Default_Model_TicketAttachment($attachment);
                $ticketAttachmentModel->save();
                unset($attachment);
            }
        }
    }
    
    public function parseMessage($mail, $message, $i)
    {
        $response = array(
            'message' => array(
                'subject'     => null,
                'fromEmail'   => null,
                'message'     => null,
                'receiveDate' => null,
            ),
            'attachments' => array()
        );
        // Get the UTC timestamp
        $timezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $receiveDate = strtotime($message->date);
        date_default_timezone_set($timezone);
        
        // Get from
        $from = $message->from;
        $from = str_replace(array('<', '>'), '', $from);
        $from = explode(' ', $from);
        $from = array_pop($from);
        $response['message']['fromEmail'] = $from;
        
        // Get the message
        $messageBody = '';
        
        $boundary = $message->getHeaderField('content-type', 'boundary');
        
        if (stristr($message->contentType, 'text/')) {
            $messageBody = $mail->getRawContent($i);
        } else if (stristr($message->contentType, 'multipart/')) {
            $messageParts = new Zend_Mail_Part(array(
                'handler' => &$mail,
                'id'      => $i,
                'headers' => $message->getHeaders()
            ));
            
            // Get the messages's contents. When fetched it removes the
            // message
            $messageParts->getContent();
            
            foreach ($messageParts as $partIndex => $part) {
                $attachment = array(
                    'ticketId'     => null,
                    'replyId'      => null,
                    'filename'     => null,
                    'contentType'  => null,
                    'content'      => null
                );
                if ($partIndex === 1) {
                    // Decode attachment's data
                    $content = (string) $part;
                    
                    if ($part->headerExists('content-transfer-encoding') and $part->contentTransferEncoding === 'base64') {
                        $content = base64_decode($content);
                    }
                    
                    //-- If an email is set with html + attachment + text alternative, then zend doesn't pickup the sub boundary :(
                    $sub_boundary = preg_match('([a-zA-Z0-9]{28})', $content, $sub_boundaries);
                    if ($sub_boundary > 0) {
                        $subparts = explode('--' . $sub_boundaries[0], $content);
                        foreach ($subparts as $subpart) {
                            if (stristr($subpart, 'text/html')) {
                                $quoted = false;
                                if (stristr($subpart, 'quoted-printable')) {
                                    $quoted = true;
                                }
                                $content = explode("\n\n", $subpart);
                                array_shift($content);
                                $content = implode("\n\n", $content);
                                if ($quoted) { 
                                    $content = Zend_Mime_Decode::decodeQuotedPrintable($content);
                                    // Gay ass word wrapping with hmtl is bad idea.
                                    $content = str_replace(array("=\n", '=3D'), array('', '='), $content);
                                }
                            }
                        }
                    }
                    
                    $content = nl2br($content);
                    
                    $messageBody = $content;
                } else {
                    $attachment['contentType'] = $part->contentType;
                    
                    // Grab the filename
                    /*
                    preg_match('/name=\"(?<filename>[^\"]+)/', $part->contentType, $matches);
                    if ($matches !== false and isset($matches['filename'])) {
                        $attachment['filename'] = $matches['filename'];
                    }
                    */
                    $attachment['filename'] = $part->getHeaderField('content-type', 'name');
                    
                    // Decode attachment's data
                    $content = (string) $part;
                    if ($part->contentTransferEncoding === 'base64') {
                        $content = base64_decode($content);
                    }
                    
                    // TODO: other encodings?
                    $attachment['content'] = $content;
                    array_push($response['attachments'], $attachment);
                }
            }
        }    
        
        $response['message']['subject']     = (string) $message->subject;
        $response['message']['fromDate']    = (string) $message->from;
        $response['message']['message']     = $messageBody;
        $response['message']['receiveDate'] = $receiveDate;
        
        return $response;
    }
}
?>
