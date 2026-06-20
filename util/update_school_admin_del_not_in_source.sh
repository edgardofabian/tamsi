path=$(dirname "$0")
echo "Uploading local code to school.gahum.net's Admin folder deleting files not in source...."
rsync -zvurl -e "ssh -p 65002" ../../public_html/school/_01Admin.dir/  u597219769@191.96.56.198:domains/gahum.net/public_html/school/_01Admin.dir --exclude=.git --exclude=util --exclude=view_bak --exclude=model_bak --exclude=controller_bak --exclude=config --exclude=vendor --exclude=js --exclude=old --exclude=db_util/db_util.cfg.local.php --exclude=uploads --exclude=_02Books.dir --exclude=images --exclude=img --delete

