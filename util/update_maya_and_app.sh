rsync -zvurl -e "ssh -p 65002" ../tamsi u635010524@185.232.14.1:domains/gahum.tech/school/   --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php
rsync -zvurl -e "ssh -p 65002" ../app u635010524@185.232.14.1:domains/gahum.tech/school/   --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php

