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


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

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
-- Name: ponies_shortname; Type: CONSTRAINT; Schema: public; Owner: mlpnow; Tablespace: 
--

ALTER TABLE ONLY ponies
    ADD CONSTRAINT ponies_shortname PRIMARY KEY (shortname);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO mlpnow;


--
-- PostgreSQL database dump complete
--

