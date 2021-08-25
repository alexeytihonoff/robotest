create table dismission_reason
(
    id          int auto_increment
        primary key,
    name        varchar(45)  not null,
    description varchar(255) null,
    created_at  timestamp    not null,
    updated_at  timestamp    not null
);

create table position
(
    id          int auto_increment
        primary key,
    name        varchar(45)  not null,
    description varchar(255) null,
    salary      int          not null,
    created_at  timestamp    not null,
    updated_at  timestamp    not null,
    is_active   tinyint(1)   not null
);

create table department
(
    id          int auto_increment
        primary key,
    leader_id   int          not null,
    name        varchar(45)  not null,
    description varchar(255) null,
    created_ad  timestamp    not null,
    update_at   timestamp    not null,
    constraint department_position_id_fk
        foreign key (leader_id) references position (id)
);

create index department_leader_id_index
    on department (leader_id);

create table user
(
    id            int auto_increment
        primary key,
    first_name    varchar(45) not null,
    last_name     varchar(45) not null,
    middle_name   varchar(45) not null,
    data_of_birth date        not null,
    created_at    timestamp   not null,
    update_at     timestamp   not null
);

create table user_dismission
(
    id         int auto_increment
        primary key,
    user_id    int        not null,
    reason_id  int        null,
    is_active  tinyint(1) not null,
    created_at timestamp  not null,
    update_at  timestamp  not null,
    constraint user_dismission_dismission_reason_id_fk
        foreign key (reason_id) references dismission_reason (id),
    constraint user_dismission_user_id_fk
        foreign key (user_id) references user (id)
);

create index user_dismission_reason_id_index
    on user_dismission (reason_id);

create index user_dismission_user_id_index
    on user_dismission (user_id);

create table user_position
(
    id            int auto_increment
        primary key,
    user_id       int       not null,
    department_id int       not null,
    position_id   int       not null,
    created_at    timestamp not null,
    update_at     timestamp not null,
    constraint user_position_department_id_fk
        foreign key (department_id) references department (id),
    constraint user_position_position_id_fk
        foreign key (position_id) references position (id),
    constraint user_position_user_id_fk
        foreign key (user_id) references user (id)
);

create index user_position_department_id_index
    on user_position (department_id);

create index user_position_position_id_index
    on user_position (position_id);

create index user_position_user_id_index
    on user_position (user_id);