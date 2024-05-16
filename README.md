## Installation ans useful commands

To be able to use the API for the first time, launch the following command in a terminal:
```bash
  make prepare
```
This command will build the Docker images, start them up, install composer dependencies, and run database migrations. With this, everything will be ready to use the API.

The next times you want to start the project, it will only be necessary to execute the following command:
```bash
  make up
```
To run the tests:
```bash
  make tests
```
To open a terminal in the container:
```bash
  make bash
```
With the following commands, you will leave the database unchanged, as if just migrated, for both testing and development:
```bash
  make db-fresh
  make test/db-fresh
```

## Documentation

### Add Seller

Create and save a seller in the database.

**URL**

http://localhost:9095/seller/add

**Parameters**

| Name   | Type   | Description             |
|--------|--------|-------------------------|
| name   | string | Name Seller             |

**Response**
- 201 OK:
```json
{
   "response":"Seller is saved successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter name is required"
}
```

### Remove Seller

Remove seller in the database.

**URL**

http://localhost:9095/seller/remove

**Parameters**

| Name | Type   | Description |
|------|--------|-------------|
| id   | string | Id Seller   |

**Response**
- 201 OK:
```json
{
   "response":"Seller is remove successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter id is required"
}
```


### Add Product

Add and save product in the database.

**URL**

http://localhost:9095/product/add

**Parameters**

| Name     | Type   | Description   |
|----------|--------|---------------|
| sellerId | string | Id Seller     |
| name     | string | Name Product  |
| price    | int    | Price Product |


**Response**
- 201 OK:
```json
{
   "response":"Product is add successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter sellerId is required"
}
```
- 404 OK:
```json
{
   "response":"Seller not found"
}
```

### Remove Product

Remove product in the database.

**URL**

http://localhost:9095/product/remove

**Parameters**

| Name  | Type   | Description   |
|-------|--------|---------------|
| id    | string | Id Product    |

**Response**
- 201 OK:
```json
{
   "response":"Product is remove successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter id is required"
}
```

### Add Cart

Add cart and save in the database.

**URL**

http://localhost:9095/cart/add

**Response**
- 201 OK:
```json
{
   "response":"Cart is add successfully"
}
```

### Add Item

Add item cart and save in the database.

**URL**

http://localhost:9095/cart/item/add

**Parameters**

| Name       | Type   | Description |
|------------|--------|-------------|
| cart_id    | string | Id cart     |
| product_id | string | Id product  |

**Response**
- 201 OK:
```json
{
   "response":"Cart item is add successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter cart_id is required"
}
```

### Remove Item

Remove item cart in the database.

**URL**

http://localhost:9095/cart/item/remove

**Parameters**

| Name       | Type   | Description |
|------------|--------|-------------|
| cart_id    | string | Id cart     |
| product_id | string | Id product  |

**Response**
- 201 OK:
```json
{
   "response":"Cart item is removed successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter cart_id is required"
}
```

### Total Amount

Calculate total amount cart

**URL**

http://localhost:9095/cart/item/total_amount

**Parameters**

| Name       | Type   | Description |
|------------|--------|-------------|
| cart_id    | string | Id cart     |

**Response**
- 201 OK:
```json
{
   "response":"Total amount is 40 $"
}
```
- 400 OK:
```json
{
   "response":"Parameter cart_id is required"
}
```

### Increment Item

Increment number of item in cart

**URL**

http://localhost:9095/cart/item/increment

**Parameters**

| Name       | Type   | Description |
|------------|--------|-------------|
| cart_id    | string | Id cart     |
| product_id | string | Id product  |

**Response**
- 201 OK:
```json
{
   "response":"Item increase successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter cart_id is required"
}
```

### Decrease Item

Decrease number of item in cart

**URL**

http://localhost:9095/cart/item/decrease

**Parameters**

| Name       | Type   | Description |
|------------|--------|-------------|
| cart_id    | string | Id cart     |
| product_id | string | Id product  |

**Response**
- 201 OK:
```json
{
   "response":"Item decrease successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter cart_id is required"
}
```

### Remove All items to cart

Remove All items to cart

**URL**

http://localhost:9095/cart/item/remove_all

**Parameters**

| Name       | Type   | Description |
|------------|--------|-------------|
| cart_id    | string | Id cart     |

**Response**
- 201 OK:
```json
{
   "response":"Items removed successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter cart_id is required"
}
```

### Confirmed buy cart

Confirmed buy cart

**URL**

http://localhost:9095/cart/item/confirmed_cart

**Parameters**

| Name       | Type   | Description |
|------------|--------|-------------|
| cart_id    | string | Id cart     |

**Response**
- 201 OK:
```json
{
   "response":"Cart is confirmed successfully"
}
```
- 400 OK:
```json
{
   "response":"Parameter cart_id is required"
}
```