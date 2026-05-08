create table urls (
    id serial primary key,
    original_url text not null,
    custom_alias varchar(50) not null unique,
    created_at timestamp with time zone default now()
)

CREATE UNIQUE INDEX idx_custom_alias ON urls (custom_alias);