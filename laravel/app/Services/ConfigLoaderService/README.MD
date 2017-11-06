# ConfigLoaderService

This service aims to provide a mechanism for system configuration storage/retrieval.

### Mock

This implementation stores the configuration in an array for testing purposes. The configuration
is not persisted between page loads.

### Database

This implementation stores the configuration in the `config` database table. This relies on the
`App\Models\Config` model, and its associated database table.
