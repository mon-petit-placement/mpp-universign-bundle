Configuration
=============

**[⬆ back to readme](../../README.md)**


```yaml
mpp_universign:
    entrypoint:
        url: "%env(UNIVERSIGN_ENTRYPOINT_URL)%"
    options:
        registration_callback_route_name: ~
        success_redirection_route_name: ~
        cancel_redirection_route_name: ~
        fail_redirection_route_name: ~
```