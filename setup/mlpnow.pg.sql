--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: oauth__deviantart; Type: TABLE; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE TABLE oauth__deviantart (
    remote_id uuid NOT NULL,
    remote_name character varying(20) NOT NULL,
    remote_avatar character varying(255) NOT NULL,
    local_id uuid NOT NULL
);


ALTER TABLE oauth__deviantart OWNER TO mlpnow;

--
-- Name: oauth__google; Type: TABLE; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE TABLE oauth__google (
    remote_id character varying(50) NOT NULL,
    remote_name character varying(255) NOT NULL,
    remote_avatar character varying(255) NOT NULL,
    local_id uuid NOT NULL
);


ALTER TABLE oauth__google OWNER TO mlpnow;

--
-- Name: ponies; Type: TABLE; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE TABLE ponies (
    shortname character varying(2) NOT NULL,
    longname character varying(20) NOT NULL,
    textcolor character(7) NOT NULL,
    bgcolor character(7) NOT NULL,
    color character(7) NOT NULL,
    favme character varying(10),
    vector character varying(20) NOT NULL,
    oc boolean DEFAULT false NOT NULL
);


ALTER TABLE ponies OWNER TO mlpnow;

--
-- Name: prefs; Type: TABLE; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE TABLE prefs (
    "user" uuid NOT NULL,
    pony character varying(10),
    sort character varying(6) DEFAULT 'abc'::character varying NOT NULL,
    timeformat character varying(2) DEFAULT '24'::character varying NOT NULL,
    bottom boolean DEFAULT false NOT NULL
);


ALTER TABLE prefs OWNER TO mlpnow;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE TABLE roles (
    value integer NOT NULL,
    name character varying(10) NOT NULL,
    label character varying(20) NOT NULL
);


ALTER TABLE roles OWNER TO mlpnow;

--
-- Name: sessions; Type: TABLE; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE TABLE sessions (
    id integer NOT NULL,
    "user" uuid NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    lastvisit timestamp without time zone DEFAULT now() NOT NULL,
    token character varying(40) NOT NULL,
    browser_name character varying(50),
    browser_ver character varying(50),
    user_agent character varying(300),
    platform character varying(50) NOT NULL
);


ALTER TABLE sessions OWNER TO mlpnow;

--
-- Name: sessions_id_seq; Type: SEQUENCE; Schema: public; Owner: mlpnow
--

CREATE SEQUENCE sessions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE sessions_id_seq OWNER TO mlpnow;

--
-- Name: sessions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mlpnow
--

ALTER SEQUENCE sessions_id_seq OWNED BY sessions.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE TABLE users (
    local_id uuid DEFAULT uuid_generate_v4() NOT NULL,
    role character varying(10) DEFAULT 'user'::character varying NOT NULL,
    name character varying(255) NOT NULL,
    avatar_url character varying(255) NOT NULL
);


ALTER TABLE users OWNER TO mlpnow;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: mlpnow
--

ALTER TABLE ONLY sessions ALTER COLUMN id SET DEFAULT nextval('sessions_id_seq'::regclass);


--
-- Name: oauth__deviantart_local_id; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY oauth__deviantart
    ADD CONSTRAINT oauth__deviantart_local_id UNIQUE (local_id);


--
-- Name: oauth__deviantart_vendor_id; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY oauth__deviantart
    ADD CONSTRAINT oauth__deviantart_vendor_id PRIMARY KEY (remote_id);


--
-- Name: ponies_shortname; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY ponies
    ADD CONSTRAINT ponies_shortname PRIMARY KEY (shortname);


--
-- Name: prefs_user; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY prefs
    ADD CONSTRAINT prefs_user PRIMARY KEY ("user");


--
-- Name: roles_name; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_name UNIQUE (name);


--
-- Name: roles_value; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_value PRIMARY KEY (value);


--
-- Name: sessions_id; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY sessions
    ADD CONSTRAINT sessions_id PRIMARY KEY (id);


--
-- Name: users_local_id; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_local_id PRIMARY KEY (local_id);


--
-- Name: prefs_pony; Type: INDEX; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE INDEX prefs_pony ON prefs USING btree (pony);


--
-- Name: sessions_user; Type: INDEX; Schema: public; Owner: mlpnow; Tablespace: 
--

CREATE INDEX sessions_user ON sessions USING btree ("user");


--
-- Name: oauth__deviantart_local_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: mlpnow
--

ALTER TABLE ONLY oauth__deviantart
    ADD CONSTRAINT oauth__deviantart_local_id_fkey FOREIGN KEY (local_id) REFERENCES users(local_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: prefs_pony_fkey; Type: FK CONSTRAINT; Schema: public; Owner: mlpnow
--

ALTER TABLE ONLY prefs
    ADD CONSTRAINT prefs_pony_fkey FOREIGN KEY (pony) REFERENCES ponies(shortname) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: prefs_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: mlpnow
--

ALTER TABLE ONLY prefs
    ADD CONSTRAINT prefs_user_fkey FOREIGN KEY ("user") REFERENCES users(local_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: sessions_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: mlpnow
--

ALTER TABLE ONLY sessions
    ADD CONSTRAINT sessions_user_fkey FOREIGN KEY ("user") REFERENCES users(local_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: mlpnow
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_role_fkey FOREIGN KEY (role) REFERENCES roles(name) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO mlpnow;


--
-- Name: oauth__deviantart; Type: ACL; Schema: public; Owner: mlpnow
--

REVOKE ALL ON TABLE oauth__deviantart FROM PUBLIC;
REVOKE ALL ON TABLE oauth__deviantart FROM mlpnow;
GRANT ALL ON TABLE oauth__deviantart TO mlpnow;


--
-- Name: ponies; Type: ACL; Schema: public; Owner: mlpnow
--

REVOKE ALL ON TABLE ponies FROM PUBLIC;
REVOKE ALL ON TABLE ponies FROM mlpnow;
GRANT ALL ON TABLE ponies TO mlpnow;


--
-- Name: roles; Type: ACL; Schema: public; Owner: mlpnow
--

REVOKE ALL ON TABLE roles FROM PUBLIC;
REVOKE ALL ON TABLE roles FROM mlpnow;
GRANT ALL ON TABLE roles TO mlpnow;


--
-- Name: sessions; Type: ACL; Schema: public; Owner: mlpnow
--

REVOKE ALL ON TABLE sessions FROM PUBLIC;
REVOKE ALL ON TABLE sessions FROM mlpnow;
GRANT ALL ON TABLE sessions TO mlpnow;


--
-- Name: users; Type: ACL; Schema: public; Owner: mlpnow
--

REVOKE ALL ON TABLE users FROM PUBLIC;
REVOKE ALL ON TABLE users FROM mlpnow;
GRANT ALL ON TABLE users TO mlpnow;


--
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: public; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON SEQUENCES  FROM PUBLIC;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON SEQUENCES  FROM postgres;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT SELECT,USAGE ON SEQUENCES  TO mlpnow;


--
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: public; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON TABLES  FROM PUBLIC;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public REVOKE ALL ON TABLES  FROM postgres;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON TABLES  TO mlpnow;


--
-- PostgreSQL database dump complete
--

