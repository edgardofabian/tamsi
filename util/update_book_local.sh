echo "Updating Books from school.gahum.tech to local ......"
rsync -zvurl -e "ssh -p 65002" u635010524@185.232.14.134:public_html/school/_02Books.dir /home/gahum/Projects/gahum/public_html/school/ --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php  --delete

