# git-pull-automation (js-nodejs)

> currently only support for github. gitlab is coming soon! 

## how to use it?
1. move or rename the `index.js` whatever you like.
2. in your `index.js` file configure the `Config Profile`
3. run your this service! example:   
    ```bash
    node index.js
    ```
    or for production, better you use [pm2](https://pm2.keymetrics.io)!

## optional:
you can also generate the secret token using openssl, example: 
```bash
openssl rand -hex 32
```
```bash
6f39d2be950f9c4dfc3df09fd7d43c8274f26da835427532d13abdc08b442695
```

Enjoy!  