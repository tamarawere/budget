--
-- PostgreSQL database dump
--

-- Dumped from database version 13.3
-- Dumped by pg_dump version 13.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: app_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_categories (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    category_name character varying(255) NOT NULL,
    category_desc character varying(255) NOT NULL,
    category_parent character varying(255) NOT NULL,
    status boolean DEFAULT true NOT NULL,
    created_by uuid NOT NULL,
    created_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone,
    updated_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
);


--
-- Name: app_payment_modes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_payment_modes (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    mode_name character varying(255) NOT NULL,
    mode_desc character varying(255) NOT NULL,
    status boolean DEFAULT true NOT NULL,
    user_id uuid NOT NULL,
    created_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone,
    updated_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
);


--
-- Name: app_sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_sessions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    session_id character varying(255) NOT NULL,
    session_data text NOT NULL,
    deleted character varying(255) DEFAULT '0'::character varying NOT NULL,
    created_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone,
    updated_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
);


--
-- Name: app_transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_transactions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    category_id uuid NOT NULL,
    item character varying(255) NOT NULL,
    payment_mode_id uuid NOT NULL,
    total_cost integer,
    unit_cost integer,
    quantity integer,
    uom_id uuid NOT NULL,
    user_id uuid NOT NULL,
    created_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone,
    updated_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
);


--
-- Name: app_uom; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_uom (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    uom_name character varying(255) NOT NULL,
    uom_desc character varying(255) NOT NULL,
    status boolean DEFAULT true NOT NULL,
    user_id uuid NOT NULL,
    created_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone,
    updated_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
);


--
-- Name: app_users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_users (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    username character varying(255) NOT NULL,
    fullname character varying(255),
    email character varying(255),
    password character varying(255),
    status boolean DEFAULT true NOT NULL,
    created_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone,
    updated_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
);


--
-- Data for Name: app_categories; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_categories (id, category_name, category_desc, category_parent, status, created_by, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: app_payment_modes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_payment_modes (id, mode_name, mode_desc, status, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: app_sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_sessions (id, session_id, session_data, deleted, created_at, updated_at) FROM stdin;
76634786-6277-4806-8f09-d811e3bcba1a	sg933mgl4mt8vs3lriu6ogd9b0	empty	1	2021-09-29 02:55:30+03	2021-09-29 03:05:45+03
96fec8fd-a475-496a-9f15-fb6d36bb91c8	sg933mgl4mt8vs3lriu6ogd9b0	empty	1	2021-09-29 02:55:32+03	2021-09-29 03:05:45+03
f0ff6d88-09d8-43ad-887a-c8fb9e52c784	sg933mgl4mt8vs3lriu6ogd9b0	empty	1	2021-09-29 02:57:56+03	2021-09-29 03:05:45+03
1e24c2e3-420a-4824-82e9-587959b415f6	sg933mgl4mt8vs3lriu6ogd9b0	empty	1	2021-09-29 03:00:43+03	2021-09-29 03:05:45+03
8287bd9c-a3be-4dcf-a620-86e96e3a9d59	sg933mgl4mt8vs3lriu6ogd9b0	empty	1	2021-09-29 03:01:17+03	2021-09-29 03:05:45+03
364d99b3-1081-4558-a617-f3592cbe5f85	sg933mgl4mt8vs3lriu6ogd9b0	empty	1	2021-09-29 03:01:50+03	2021-09-29 03:05:45+03
711164a5-f4dc-41b6-a275-4b44140c6887	sg933mgl4mt8vs3lriu6ogd9b0	empty	0	2021-09-29 03:05:45+03	2021-09-29 03:05:45+03
\.


--
-- Data for Name: app_transactions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_transactions (id, category_id, item, payment_mode_id, total_cost, unit_cost, quantity, uom_id, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: app_uom; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_uom (id, uom_name, uom_desc, status, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: app_users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_users (id, username, fullname, email, password, status, created_at, updated_at) FROM stdin;
\.


--
-- Name: app_categories app_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_categories
    ADD CONSTRAINT app_categories_pkey PRIMARY KEY (id);


--
-- Name: app_payment_modes app_payment_modes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_payment_modes
    ADD CONSTRAINT app_payment_modes_pkey PRIMARY KEY (id);


--
-- Name: app_sessions app_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_sessions
    ADD CONSTRAINT app_sessions_pkey PRIMARY KEY (id);


--
-- Name: app_transactions app_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_transactions
    ADD CONSTRAINT app_transactions_pkey PRIMARY KEY (id);


--
-- Name: app_uom app_uom_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_uom
    ADD CONSTRAINT app_uom_pkey PRIMARY KEY (id);


--
-- Name: app_users app_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_users
    ADD CONSTRAINT app_users_pkey PRIMARY KEY (id);


--
-- Name: app_categories fk_cat_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_categories
    ADD CONSTRAINT fk_cat_user FOREIGN KEY (created_by) REFERENCES public.app_users(id);


--
-- Name: app_payment_modes fk_modes_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_payment_modes
    ADD CONSTRAINT fk_modes_user FOREIGN KEY (user_id) REFERENCES public.app_users(id);


--
-- Name: app_uom fk_modes_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_uom
    ADD CONSTRAINT fk_modes_user FOREIGN KEY (user_id) REFERENCES public.app_users(id);


--
-- Name: app_transactions fk_modes_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_transactions
    ADD CONSTRAINT fk_modes_user FOREIGN KEY (user_id) REFERENCES public.app_users(id);


--
-- PostgreSQL database dump complete
--

