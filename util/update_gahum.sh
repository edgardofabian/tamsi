rsync -zvurl -e "ssh -p 65002" /mnt/lan/sg4tera_important/projects/gahum/*  u635010524@185.232.14.1:public_html/ --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php --exclude=school --exclude=eshopper

