
-- Drop Constraint

ALTER TABLE product_category_relationships DROP PRIMARY KEY product_categories_relantionship_pk;

-- Add Contraint

ALTER TABLE product_category_relationships DROP PRIMARY KEY, ADD PRIMARY KEY (idCategoryParent, idCategory) USING BTREE;
