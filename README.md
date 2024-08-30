# Project setup

```bash
sail up -d
sail artisan migrate 
sail artisan db:seed --class=DatabaseSeeder 
```

If you have trouble with running sail command, you can try ./vendor/bin/sail

To ensure the application is working, open http://localhost/

# Test Running
```bash
sail artisan test --filter=ValidateSignatureTest  
```

# Docs
You can find swagger.json in the root folder. To show API documentation, you can open https://editor.swagger.io/ and upload swagger.json content

# API Authenticating
After you will run DatabaseSeeder, there will be created a user in the database. You can use this user for the /login endpoint
```json
{
    "email": "test@example.com",
    "password": "password"
}
```
