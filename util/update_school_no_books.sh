path=$(dirname "$0")
echo "Uploading local code to school.gahum.tech...."
rsync -zvurl -e "ssh -p 65002" ../../public_html/school/*  u597219769@191.96.56.198:domains/gahum.net/public_html/school/ --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor  --exclude=old --exclude=db_util/db_util.cfg.local.php --exclude=uploads  --exclude=three.js --exclude=MathJax-2.7.7 --exclude=node_modules --exclude=Experimental.dir


