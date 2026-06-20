#rsync -zvurl -e "ssh -p 65002" /mnt/sg4tera_important/projects/gahum/school/usr_generated u635010524@185.232.14.1:domains/gahum.tech/school/   --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php
rsync -zvurl -e "ssh -p 65002" ../../events u280093157@194.59.164.165:domains/docph.net/   --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config  --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php
rsync -zvurl -e "ssh -p 65002" ../../public_html/events u280093157@194.59.164.165:domains/docph.net/public_html/   --exclude=.git  --exclude=util --exclude=view_bak --exclude=model_bak  --exclude=config --exclude=vendor  --exclude=old --exclude=db_util/db_util.cfg.local.php


