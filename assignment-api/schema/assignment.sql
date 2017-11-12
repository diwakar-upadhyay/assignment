CREATE TABLE owner (
    po_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    po_name character varying(50),
    po_email character varying(30),
    po_phone integer, 
    po_booking_limit integer,
    po_description character varying(100)
);


CREATE TABLE booking (
    bo_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    po_id integer,
    bo_name character varying(50),
    bo_email character varying(30),
    bo_phone integer, 
    bo_description text,
    bo_amount integer
);


insert into booking (po_id,bo_name,bo_email,bo_phone,bo_description,bo_amount)values(1,'deepak','deepak@gmail.com',880030334,"nehurplace new hotel",200);