# Channel Sync Demo

Mini channel manager demonstrating OTA webhook integration patterns.

## Features

- Webhook endpoints for Airbnb, VRBO, and Booking.com
- Payload normalization across different OTA formats
- Conflict detection for overlapping reservations
- Real-time dashboard with auto-refresh

## Architecture

- **Transformers:** Normalize OTA-specific payloads into a common DTO
- **Services:** Business logic and conflict detection
- **Repositories:** Data access layer

## Local Development

```bash
cd docker && docker compose up -d
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=ChannelSeeder
```

## Testing

```bash
docker compose exec app php artisan test
```

## License

MIT