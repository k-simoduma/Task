CREATE DATABASE task;

CREATE TABLE task.project(
    id int AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(50) NOT NULL
);

CREATE TABLE task.member(
    id int AUTO_INCREMENT PRIMARY KEY,
    project_id int,
    member_name VARCHAR(30) NOT NULL,
    mail_address VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE task.ticket(
    id int AUTO_INCREMENT PRIMARY KEY,
    project_id int,
    author_id int,
    charge_id int,
    add_date DATETIME,
    last_updated DATETIME,
    status_id int,
    category_id int,
    priority_id int,
    deadline DATE,
    ticket_title TEXT,
    ticket_content TEXT
);

CREATE TABLE task.timeline(
    id int AUTO_INCREMENT PRIMARY KEY,
    project_id int,
    member_id int,
    ticket_id int,
    action_id int,
    date_time DATETIME
);

CREATE TABLE task.comment(
    id int AUTO_INCREMENT PRIMARY KEY,
    project_id int,
    ticket_id int,
    author_id int,
    add_date DATETIME,
    comment_content TEXT
);

CREATE TABLE task.status(
    id int AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) NOT NULL
);

CREATE TABLE task.category(
    id int AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

CREATE TABLE task.priority(
    id int AUTO_INCREMENT PRIMARY KEY,
    priority_name VARCHAR(50) NOT NULL
);

CREATE TABLE task.action(
    id int AUTO_INCREMENT PRIMARY KEY,
    action_sentence VARCHAR(50) NOT NULL
);