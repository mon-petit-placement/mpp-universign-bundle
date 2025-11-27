Configuration
=============

**[⬆ back to readme](../../README.md)**

```yaml
// config/packages/mpp_universign.yaml
mpp_universign:
    entrypoint:
        url: "%env(UNIVERSIGN_ENTRYPOINT_URL)%"
    options:
        # registration_callback_route_name: mpp_universign_callback
        success_redirection_route_name: ~
        cancel_redirection_route_name: ~
        fail_redirection_route_name: ~
```

```yaml
// routes/mpp_universign.yaml
mpp_universign:
    resource: "@MppUniversignBundle/Controller/*.php"
    type: attribute
    prefix: /api/v1
```
