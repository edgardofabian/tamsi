rsync -zvurl -e "ssh -p 65002" ../../public_html/school/css u635010524@185.232.14.1:public_html/school/   --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php
rsync -zvurl -e "ssh -p 65002" ../../public_html/school/index.php u635010524@185.232.14.1:public_html/school/   --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php


