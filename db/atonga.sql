CREATE TABLE IF NOT EXISTS "app_users" (
        "id" UUID NOT NULL DEFAULT uuid_generate_v4(),
        "username" CHARACTER VARYING(255) NOT NULL,
        "fullname" CHARACTER VARYING(255) NULL,
        "email" CHARACTER VARYING(255) NULL,
        "password" CHARACTER VARYING(255) NULL,
        "status" BOOLEAN NOT NULL DEFAULT true,
        "created_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
        "updated_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
        PRIMARY KEY ("id")
);

CREATE TABLE IF NOT EXISTS "app_sessions" (
		"id" UUID NOT NULL DEFAULT uuid_generate_v4(),
        "session_id" CHARACTER VARYING(255) NOT NULL,
        "session_data" TEXT NOT NULL,
        "deleted" CHARACTER VARYING(255) NOT NULL,
        "created_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
        "updated_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
        PRIMARY KEY ("id")
);

CREATE TABLE IF NOT EXISTS "app_category_types" (
	"type_id" UUID NOT NULL DEFAULT uuid_generate_v4(),
	"type_name" CHARACTER VARYING(255) NOT NULL,
	"type_desc" CHARACTER VARYING(255) NOT NULL,
	"status" BOOLEAN NOT NULL DEFAULT true,
	"created_by" UUID NOT NULL,
	"created_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	"updated_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	PRIMARY KEY ("id"),
	CONSTRAINT fk_cat_user
  		FOREIGN KEY(created_by) 
	  		REFERENCES app_users(id)
);

CREATE TABLE IF NOT EXISTS "app_categories" (
	"id" UUID NOT NULL DEFAULT uuid_generate_v4(),
	"category_name" CHARACTER VARYING(255) NOT NULL,
	"category_desc" CHARACTER VARYING(255) NOT NULL,
	"category_parent" CHARACTER VARYING(255) NOT NULL,
	"category_type" CHARACTER VARYING(255) NOT NULL,
	"status" BOOLEAN NOT NULL DEFAULT true,
	"created_by" UUID NOT NULL,
	"created_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	"updated_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	PRIMARY KEY ("id"),
	CONSTRAINT fk_cat_user
  		FOREIGN KEY(created_by) 
	  		REFERENCES app_users(id)
	CONSTRAINT fk_cat_type
  		FOREIGN KEY(category_type) 
	  		REFERENCES app_category_types(type_id)
);

CREATE TABLE IF NOT EXISTS "app_payment_modes" (
	"id" UUID NOT NULL DEFAULT uuid_generate_v4(),
	"mode_name" CHARACTER VARYING(255) NOT NULL,
	"mode_desc" CHARACTER VARYING(255) NOT NULL,
	"status" BOOLEAN NOT NULL DEFAULT true,
	"user_id" UUID NOT NULL,
	"created_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	"updated_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	PRIMARY KEY ("id"),
	CONSTRAINT fk_modes_user
	      		FOREIGN KEY(user_id) 
		  		REFERENCES app_users(id)
);

CREATE TABLE IF NOT EXISTS "app_uom" (
	"id" UUID NOT NULL DEFAULT uuid_generate_v4(),
	"uom_abbrv" CHARACTER VARYING(255) NOT NULL,
	"uom_name" CHARACTER VARYING(255) NOT NULL,
	"uom_desc" CHARACTER VARYING(255) NOT NULL,
	"uom_status" BOOLEAN NOT NULL DEFAULT true,
	"user_id" UUID NOT NULL,
	"created_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	"updated_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	PRIMARY KEY ("id"),
	CONSTRAINT fk_modes_user
      		FOREIGN KEY(user_id) 
		  		REFERENCES app_users(id)
);

CREATE TABLE IF NOT EXISTS app_items (
	"item_id" UUID DEFAULT uuid_generate_v4(),
	"item_name" VARCHAR ( 255 ) UNIQUE NOT NULL,
	"item_category" UUID NOT NULL,
	"item_desc" VARCHAR ( 255 ) NULL,
	"item_uom" UUID NOT NULL,
	"created_on" TIMESTAMP NOT NULL,
	"created_by" UUID NOT NULL,
	"updated_on" TIMESTAMP NOT NULL,
	"updated_by" UUID NOT NULL,
	PRIMARY KEY ("item_id"),
	CONSTRAINT fk_category
		FOREIGN KEY(item_category) 
			REFERENCES app_categories(category_id)
				ON DELETE CASCADE,
	CONSTRAINT fk_uom
		FOREIGN KEY(item_uom) 
			REFERENCES app_uom(uom_id)
				ON DELETE CASCADE,
	CONSTRAINT fk_create
		FOREIGN KEY(created_by) 
			REFERENCES app_users(user_id)
				ON DELETE CASCADE,
	CONSTRAINT fk_update
		FOREIGN KEY(updated_by) 
			REFERENCES app_users(user_id)
				ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS app_services (
	"service_id" UUID DEFAULT uuid_generate_v4(),
	"service_name" VARCHAR ( 255 ) UNIQUE NOT NULL,
	"service_category" UUID NOT NULL,
	"service_desc" VARCHAR ( 255 ) NULL,
	"service_uom" UUID NOT NULL,
	"created_on" TIMESTAMP NOT NULL,
	"created_by" UUID NOT NULL,
	"updated_on" TIMESTAMP NOT NULL,
	"updated_by" UUID NOT NULL,
	PRIMARY KEY ("service_id"),
	CONSTRAINT fk_category
		FOREIGN KEY(item_category) 
			REFERENCES app_categories(category_id)
				ON DELETE CASCADE,
	CONSTRAINT fk_uom
		FOREIGN KEY(item_uom) 
			REFERENCES app_uom(uom_id)
				ON DELETE CASCADE,
	CONSTRAINT fk_create
		FOREIGN KEY(created_by) 
			REFERENCES app_users(user_id)
				ON DELETE CASCADE,
	CONSTRAINT fk_update
		FOREIGN KEY(updated_by) 
			REFERENCES app_users(user_id)
				ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS "app_transactions" (
	"id" UUID NOT NULL DEFAULT uuid_generate_v4(),
	"category_id" UUID NOT NULL,
	"item" CHARACTER VARYING(255) NOT NULL,
    "payment_mode_id" UUID NOT NULL,
    "total_cost" INT,
	"unit_cost" INT,
    "quantity" INT,
    "uom_id" UUID NOT NULL,
	"user_id" UUID NOT NULL,
	"created_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	"updated_at" TIMESTAMP(0) WITH TIME ZONE NULL DEFAULT NULL,
	PRIMARY KEY ("id"),
	CONSTRAINT fk_modes_user
      		FOREIGN KEY(user_id) 
		  		REFERENCES app_users(id)
);
