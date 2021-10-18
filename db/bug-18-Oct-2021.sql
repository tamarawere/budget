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
    category_id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    category_name character varying(255) NOT NULL,
    category_desc character varying(255) NOT NULL,
    category_parent character varying(255),
    status boolean DEFAULT true NOT NULL,
    created_by uuid,
    created_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone,
    updated_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
);


--
-- Name: app_old_sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_old_sessions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    session_id character varying(255) NOT NULL,
    session_data text,
    deleted_at timestamp(0) with time zone DEFAULT NULL::timestamp with time zone
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
    session_data text,
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
    user_id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
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

COPY public.app_categories (category_id, category_name, category_desc, category_parent, status, created_by, created_at, updated_at) FROM stdin;
7866b78b-c4b5-49ca-87a5-a6acc011edcf	Groceries	Groceries Desc	\N	t	7d669076-d175-4d16-a11c-42224167b9a6	2021-10-17 10:27:48+03	2021-10-17 10:27:48+03
70761860-d937-465b-94d3-bdea7c6a5544	Utilities	Main utility Expesnes		t	7d669076-d175-4d16-a11c-42224167b9a6	2021-10-18 02:08:48+03	2021-10-18 02:08:48+03
6999c6f9-71e1-4765-87ad-197b9b5374f2	Kids	About Kids	Choose...	t	7d669076-d175-4d16-a11c-42224167b9a6	2021-10-18 02:20:01+03	2021-10-18 02:20:01+03
5df46a3a-f6a8-41d0-b8f6-7b68c2408e50	Marvel	Marvel's Expenses	6999c6f9-71e1-4765-87ad-197b9b5374f2	t	7d669076-d175-4d16-a11c-42224167b9a6	2021-10-18 02:22:25+03	2021-10-18 02:22:25+03
\.


--
-- Data for Name: app_old_sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_old_sessions (id, session_id, session_data, deleted_at) FROM stdin;
230b2aeb-12dc-4388-908a-42b32c9c53f7	sg933mgl4mt8vs3lriu6ogd9b0	\N	2021-10-14 02:14:35+03
d6b6c616-264a-4d50-aa24-e502fe854286	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:49:42+03
677f977c-eda6-4ac3-b8a7-001572a176c8	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:49:43+03
c895bbb9-f31c-4460-8419-8bfdae0509c2	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:49:46+03
2534fdc5-d344-401c-bfda-849591d55e60	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:49:47+03
7d4b85d3-502c-46f0-bcb5-da820e7d63e6	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:50:14+03
c5a3e508-1774-4a37-a511-4858bbc6f7c1	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:50:14+03
a2fe368b-7cab-4c42-a50b-0a2883fcc2c8	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:53:06+03
fcb75bb4-2477-4e36-aa54-ecfb3bd4da6d	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:53:07+03
7d68ed11-7927-498a-91b9-31ce706a6fa7	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:53:19+03
300d24dd-3412-4cae-a33a-1755a3b2f553	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 02:53:19+03
1e0722ef-401d-40cb-8db4-a3618d4ea2c3	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:20:34+03
cf301266-ba13-4df7-ac9f-7377f999605c	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:20:44+03
e65039fa-1bbc-4657-9e4e-73f26f7c0d1e	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:20:44+03
9030a6aa-03f8-4743-af6b-61698d68b3cc	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:23:53+03
553fcefc-91aa-4659-b358-100f11aa487d	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:23:53+03
792fc19b-b6ed-4cef-ae61-ef0b512b4b6e	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:24:31+03
df98990f-f23e-416f-8e23-afd19baabf4d	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:24:31+03
2f78a47c-b7c1-4a3c-bcb1-90bdeefd2976	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:25:40+03
88b6b2e6-c2dd-4309-ad87-c6f186c712b3	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:25:40+03
fae5cc5f-5dd4-45aa-b861-2cf580eee98e	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:28:10+03
aaadfe39-891d-4d96-bcb9-cf2f7e6278e5	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:28:18+03
9b94c18d-13e1-4287-9bca-a029eb5fb15e	sg933mgl4mt8vs3lriu6ogd9b0	empty	2021-10-14 03:28:19+03
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
7d669076-d175-4d16-a11c-42224167b9a6	sg933mgl4mt8vs3lriu6ogd9b0	empty	0	2021-10-14 03:28:19+03	2021-10-14 03:28:19+03
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

COPY public.app_users (user_id, username, fullname, email, password, status, created_at, updated_at) FROM stdin;
63088b03-a7cc-4482-92cc-93178c3921b3	test	testuser	test@me.me	$2y$10$b61xhjRNQ8F4wRNgKd5v6u4zUs7ybm7.JwwHd8ENIDGuT4CtBeGdq	t	2021-09-30 01:37:13+03	2021-09-30 01:37:13+03
7d669076-d175-4d16-a11c-42224167b9a6	admin	administrator	admin@ug.bug	1233344444	t	\N	\N
\.


--
-- Name: app_categories app_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_categories
    ADD CONSTRAINT app_categories_pkey PRIMARY KEY (category_id);


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
    ADD CONSTRAINT app_users_pkey PRIMARY KEY (user_id);


--
-- Name: app_sessions_session_id_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX app_sessions_session_id_idx ON public.app_sessions USING btree (session_id);


--
-- Name: app_categories fk_cat_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_categories
    ADD CONSTRAINT fk_cat_user FOREIGN KEY (created_by) REFERENCES public.app_users(user_id);


--
-- Name: app_payment_modes fk_modes_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_payment_modes
    ADD CONSTRAINT fk_modes_user FOREIGN KEY (user_id) REFERENCES public.app_users(user_id);


--
-- Name: app_uom fk_modes_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_uom
    ADD CONSTRAINT fk_modes_user FOREIGN KEY (user_id) REFERENCES public.app_users(user_id);


--
-- Name: app_transactions fk_modes_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_transactions
    ADD CONSTRAINT fk_modes_user FOREIGN KEY (user_id) REFERENCES public.app_users(user_id);


--
-- PostgreSQL database dump complete
--

