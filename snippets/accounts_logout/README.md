# AJAX logout 
This snippet need for logout into frontend.

# How to use ?
1. Create new resource without caching
2. Change resource template to empty
3. Change content type to JSON
4. Add snippet execution to resource content
4. Send AJAX logout requests from your scripts :-)

# Response
Snippet return JSON response. 
In response we need use code object. When code equals 0 all good and user logged out, if code equals -1 snippet have errors when did executed.

# Response examples

## Good
```javascript
{
  "code": 0,
  "message": "Success logout"
}
```

## Bad
```javascript
{
  "code": -1,
  "message": "..."
}
```
