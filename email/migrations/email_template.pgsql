CREATE TABLE email_template (
    id integer NOT NULL,
    name character varying(255),
    subject character varying(255),
    heading character varying(255),
    message text
);
CREATE SEQUENCE email_template_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE ONLY email_template
    ADD CONSTRAINT email_template_pkey PRIMARY KEY (id),
	ALTER COLUMN id SET DEFAULT nextval('email_template_id_seq'::regclass);