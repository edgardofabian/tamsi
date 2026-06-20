rsync -zvurl -e "ssh -p 65002" /home/gahum/Projects/gahum/public_html/school/_02Books.dir u635010524@185.232.14.134:public_html/school/  --delete --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php

