rsync -zvurl -e "ssh -p 65002" /home/gahum/Projects/gahum/*  u597219769@191.96.56.198:domains/gahum.net/ --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php --exclude=school --exclude=eshopper

