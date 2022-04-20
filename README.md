# Base Http Client

## Usage

### Create credential for Basic Auth
Extends from `LiaTec\Http\Credential` and create custom class credential
```php

use LiaTec\Http\Credential;

class MyCustomCredential extends Credential
{
    /**
    * Init any value
    *
    * @return void
    */
    public function boot()
    {
        
    }
    
    /**
    * Modifies request headers if you need
    *
    * @return void
    */
    public function request()
    {
        // $this->header('Test', 'Testing');        
    }

    /**
     * Prepare payload for Basic auth token
     * username and password values are required
     */
    public function getTokenRequestParameters(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
```
Get basic client from factory
```php
$client = Http::basic(
    new MyCustomCredential(['username' => $this->username, 'password' => $this->password])
)->protocol('https')->baseUrl("domain.com");
```

Make calls

```php
$client->get('resourceName'); // calls: GET https://domain.com/resourceName
$client->post('resourceName',[ 'payload'=>'value', 'more'=> true ]);
$client->put('resourceName',[ 'payload'=>'value', 'more'=> true ]);
$client->patch('resourceName',[ 'payload'=>'value', 'more'=> true ]);
```

Every call gets the `Authorization: Basic <token>` header, where `<token>` is made with `base64_encode("{username}:{password}")`