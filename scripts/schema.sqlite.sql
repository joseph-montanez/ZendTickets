
CREATE TABLE product (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(32) NULL,
    name VARCHAR(80) NULL,
    description TEXT NULL,
    listPrice DECIMAL(8,2) NULL,
    created DATETIME NOT NULL
);

CREATE TABLE tickets (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    fromEmail VARCHAR(255) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NULL,
    receiveDate INTEGER NULL
);

CREATE TABLE ticket_attachments (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    ticketId INTEGER NOT NULL,
    replyId INTEGER NOT NULL DEFAULT 0,
    filename VARCHAR(255) NULL,
    contentType VARCHAR(255) NULL,
    content TEXT NULL
);

CREATE TABLE ticket_replies (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    ticketId INTEGER NOT NULL,
    fromEmail VARCHAR(255) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NULL,
    receiveDate INTEGER NULL
);

CREATE INDEX "product_id" ON "product" ("id");
CREATE INDEX "ticket_id" ON "tickets" ("id");
CREATE INDEX "ticket_reply_id" ON "ticket_replies" ("id");
CREATE INDEX "ticket_attachment_id" ON "ticket_attachments" ("id");
