# Deploy (temporary, for the project defense on 2026-11-18)

Every push to `main` deploys the app to our VPS through `.github/workflows/deploy.yml`.
The site runs until about 2026-11-18 midday; then it is taken down.

## How it works

```
push to main
  -> GitHub Actions packs the repo (git archive) and sends it over SSH
  -> VPS: /opt/swbo/deploy.sh unpacks it and runs `docker compose up -d --build`
  -> Caddy (ports 80/443, automatic HTTPS) -> frontend nginx -> Laravel -> MySQL
```

- The SSH key can only run `deploy.sh`: no shell, no tunnels. It expires on 2026-11-19 00:00.
- The server uses its own copy of `compose.prod.yml` (`/opt/swbo/compose.yml`). A change to this
  file in the repo does nothing until someone copies it to the server.
- The database is loaded from `docker/mysql/init/*.sql` on the first start only.
  Later deploys keep the data.

## One-time setup for the repo admin

Add one repository secret: Settings -> Secrets and variables -> Actions -> New repository secret.

- Name: `DEPLOY_SSH_KEY`
- Value: the private key Jakub sends you (the whole file, including the BEGIN/END lines).

Until the secret exists, the workflow skips the deploy with a warning. It does not fail.
To deploy without a push: Actions -> "Deploy to VPS" -> Run workflow.

## Take it down

On the VPS: `sudo bash /opt/swbo/undo.sh` (add `--purge` to also delete the database and images).
