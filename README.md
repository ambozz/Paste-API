# Paste-API
Simple API for https://github.com/amboss-dev/Paste

# Install 

## Same Directory as Pasting Site
1. Download **api.php** to your Webserver.
2. Done.

## Different Directory as Pasting Site
1. Download Source Code to your Webserver.
2. Edit **config.php** to fill in your Database Configuration.
5. Done.

# Documentation

## Create Paste
Example Request:
```
GET api.php?action=create&content=test
```

Example Response:
```
{"error":false,"id":"62a5439ac5917"}
```

## Get Paste
Example Request:
```
GET api.php?action=get&id=62a5439ac5917
```

Example Response:
```
{"error":false,"content":"test","create_time":1654997914}
```

## Error Messages

### General
- **ACTION_UNDEFINED**: No action GET Parameter set.
- **ACTION_INVALID**: action GET Parameter does not contain a valid action.

### Create Paste
- **CONTENT_UNDEFINED**: No content GET Parameter set.
- **CONTENT_EMPTY**: content GET Parameter is empty.
- **CREATE_FAILED**: Failed to insert paste into database.

### Get Paste
- **ID_UNDEFINED**: No id GET Parameter set.
- **ID_EMPTY**: id GET Parameter is empty.
- **PASTE_NOT_FOUND**: A paste with that id does not exist.
