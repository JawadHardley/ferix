echo '#!/bin/bash
exec php8.1 "$@"' > .php
chmod +x .php