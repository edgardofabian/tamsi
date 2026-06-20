path=$(dirname "$0")
sh ${path}/update_book_local.sh
echo "Uploading local code to school.gahum.tech...."
rsync -zvurl -e "ssh -p 65002" /home/gahum/projects/school/*  u635010524@185.232.14.1:public_html/school/ --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php

