
-- Drop Foreign Keys

ALTER TABLE product_category_relationships DROP FOREIGN KEY product_categories_relantionship_cat_fk;
ALTER TABLE product_category_relationships DROP FOREIGN KEY product_categories_relantionship_rel_fk;
ALTER TABLE product_category_relationships DROP FOREIGN KEY product_categories_relantionship_type_fk;
ALTER TABLE product_category_relationships DROP INDEX product_categories_relantionship_cat_fk;
ALTER TABLE product_category_relationships DROP INDEX product_categories_relantionship_type_fk;
ALTER TABLE product_category_relationships DROP PRIMARY KEY;

-- Add Constraint

ALTER TABLE product_category_relationships ADD CONSTRAINT product_category_relationships_pk PRIMARY KEY (idCategoryParent, idCategory);
ALTER TABLE product_category_relationships ADD CONSTRAINT product_category_relationships_cat_fk FOREIGN KEY (idCategoryParent) REFERENCES product_categories(id);
ALTER TABLE product_category_relationships ADD CONSTRAINT product_category_relationships_rel_fk FOREIGN KEY (idCategory) REFERENCES product_categories(id);
ALTER TABLE product_category_relationships ADD CONSTRAINT product_category_relationships_type_fk FOREIGN KEY (idCategoryType) REFERENCES product_category_types(id);
