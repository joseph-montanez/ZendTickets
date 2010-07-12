CREATE TABLE accounts (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(255) NULL,
    passwd VARCHAR(255) NULL,
    registerDate INTEGER NULL,
    lastLoginDate INTEGER NULL
);
CREATE INDEX "accountId" ON "accounts" ("id");

CREATE TABLE mailAccounts (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    smtpUsername VARCHAR(255) NULL,
    smtpPassword VARCHAR(255) NULL,
    smtpSSL VARCHAR(10) NULL,
    smtpPort INTEGER NULL
    subject VARCHAR(255) NULL,
    message TEXT NULL,
    receiveDate INTEGER NULL
);
CREATE INDEX "mailAccountId" ON "mailAccounts" ("id");

CREATE TABLE tickets (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    fromEmail VARCHAR(255) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NULL,
    receiveDate INTEGER NULL
);
CREATE INDEX "ticketId" ON "tickets" ("id");

CREATE TABLE ticketAttachments (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    ticketId INTEGER NOT NULL,
    replyId INTEGER NOT NULL DEFAULT 0,
    filename VARCHAR(255) NULL,
    contentType VARCHAR(255) NULL,
    content TEXT NULL
);
CREATE INDEX "ticketAttachmentId" ON "ticketAttachments" ("id");

CREATE TABLE ticketReplies (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    ticketId INTEGER NOT NULL,
    fromEmail VARCHAR(255) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NULL,
    receiveDate INTEGER NULL
);
CREATE INDEX "ticketReplyId" ON "ticketReplies" ("id");
