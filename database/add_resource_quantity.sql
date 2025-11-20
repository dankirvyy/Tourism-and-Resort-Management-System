-- Add quantity tracking columns to resources table
-- quantity = total number of this resource (e.g., 5 cars)
-- available_quantity = currently available (e.g., 3 cars available)
-- For individual resources like guides with names, quantity will be 1

ALTER TABLE resources 
ADD COLUMN quantity INT DEFAULT 1 AFTER capacity,
ADD COLUMN available_quantity INT DEFAULT 1 AFTER quantity;

-- Update existing resources to have quantity = 1 (individual items)
UPDATE resources SET quantity = 1, available_quantity = 1 WHERE quantity IS NULL;

-- Make quantity NOT NULL after setting defaults
ALTER TABLE resources 
MODIFY COLUMN quantity INT NOT NULL DEFAULT 1,
MODIFY COLUMN available_quantity INT NOT NULL DEFAULT 1;
