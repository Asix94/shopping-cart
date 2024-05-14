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

### Create Seller

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
- 500 OK:
```json
{
   "response":"Parameter name is required"
}
```

