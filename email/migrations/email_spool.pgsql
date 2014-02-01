CREATE TABLE email_spool (
    id integer NOT NULL,
    transport character varying(32),
    template character varying(32),
    priority integer,
    status character varying(32),
    model_name character varying(255),
    model_id character varying(255),
    to_address character varying(255),
    from_address character varying(255),
    subject character varying(255),
    message text,
    sent integer,
    created integer
);
CREATE SEQUENCE email_spool_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE ONLY email_spool
    ADD CONSTRAINT email_spool_pkey PRIMARY KEY (id),
	ALTER COLUMN id SET DEFAULT nextval('email_spool_id_seq'::regclass);