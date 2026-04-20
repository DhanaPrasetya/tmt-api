create table links (
    id serial primary key,
    original_url text not null,
    short_url varchar(255) not null unique,
    created_at timestamp with time zone default now()
)

CREATE UNIQUE INDEX idx_short_url ON links (short_url);