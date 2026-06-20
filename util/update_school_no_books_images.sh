path=$(dirname "$0")
echo "Uploading local code to school.gahum.tech...."
rsync -zvurl -e "ssh -p 65002" ../../public_html/school/*  u635010524@185.232.14.134:public_html/school/ --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php --exclude=uploads --exclude=_02Books.dir --exclude=images --exclude=img

