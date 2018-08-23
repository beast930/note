Git的学习
	1.git init
	2.git add readme.txt
	3.git commit -m "a readme.txt"
	4.git status //当前状态,修改文件时,它会记录文件的修改状态
	5.git diff //查看当前修改的文件与提交的文件的差别
	6.修改之后 进行 add commit 操作
	7.git log [--pretty=online]//查看提交的记录

	HEAD表示当前的版本,HEAD^表示上一个,一次类推, --> HEAD~100回退100个
	8.git reset --hard HEAD^//回退上一个版本
		git reset --hard 6f0aa7235e //回退指定版本 向前/向后
		重返未来，用git reflog查看命令历史，以便确定要回到未来的哪个版本
	9.git diff HEAD -- readme.txt //查看当前指针下的readme.txt与之前的对比

	//撤销
	10.git checkout -- readme.txt 
		命令git checkout -- readme.txt意思就是，把readme.txt文件在工作区的修改全部撤销，这里有两种情况：
		一种是readme.txt自修改后还没有被放到暂存区，现在，撤销修改就回到和版本库一模一样的状态；
		一种是readme.txt已经添加到暂存区后，又作了修改，现在，撤销修改就回到添加到暂存区后的状态。
		总之，就是让这个文件回到最近一次git commit或git add时的状态。
	11.git reset HEAD <file>可以把暂存区的修改撤销掉（unstage），重新放回工作区：
			git reset HEAD readme.txt//将文件从暂存区回放到工作区
			git checkout -- readme.txt//将工作区的文件撤销修改

	//删除 删本地文件,删版本库文件,commit
	1.rm test.txt
	2.git rm test.txt
	3.git commit -m "delete"

	//误删除恢复
	1.rm test.txt
	2.git checkout -- test.txt

	//与远程仓库相关联 在这之前 需要在github的设置中添加本机的公钥, 
	git remote add origin git@github.com:beast930/Sample.git
	//远程库的名字就是origin，这是Git默认的叫法

	//将本地的东西放到远程仓库中
	git push -u origin master//用git push命令，实际上是把当前分支master推送到远程。
	//以后可以使用git push origin master 将本地master分支push到远程的仓库

	//克隆
	 git clone git@github.com:beast930/Hello.git

	//创建并切换到dev分支
	git checkout -b dev //相当于 git branch dev; git checkout dev
	//查看分支
	git branch
	//返回master分支进行融合
	1.git checkout master
	2.git merge dev
	3.git branch -d dev//融合后删除分支

	//解决融合出错
	1.vim readme.txt
	2.删除不想要的
	3.git add readme.txt 和 git commit -m "..."

	//分支管理策略
	git merge --no-ff -m "merge with no-ff" dev //融合的时候同时在一个新的节点上融合

	//bug管理
	当已经有文件在暂存区中, 如果想切换分支去修改另一个文件的bug, 必须要先用 git stash将原先暂存区的内容隐藏,再在操作另一个分支的事(若不选择隐藏,在其他分支commit的时候会被一起提交到其他分支),当master与其他分支融合后,切换到本分支,使用
	git stash pop 显示, 在进行操作

	//多人协作 	角色:我, 小明
	1.我在提交的时候使用 git push origin dev//将dev分支的内容发送到github, 并将远程仓库创建dev分支
	2.小明想要拿到我代码 git clone git@github.com:beast930/Hello.git
	此时他的本地git只有master分支, 使用 git checkout -b dev origin/dev 在本地创建dev分支并与origin/dev
	相连
	3.小明修改, git push origin dev

	4.遇到出错, 先git pull ,再将合并的内容修改, 然后git push

	在本地创建和远程分支对应的分支，使用git checkout -b branch-name origin/branch-name，本地和远程分支的名称最好一致；
	建立本地分支和远程分支的关联，使用git branch --set-upstream branch-name origin/branch-name；
	从远程抓取分支，使用git pull，如果有冲突，要先处理冲突。


	//如果要丢弃一个没有被合并过的分支，可以通过git branch -D <name>强行删除。

	//使用github
	1.fork按钮复制到自己的github仓库
	2.点击获得自己的clone地址
	3.使用命令git clone 获得项目
	
	git remote add origin  git@gitlab.shiyuegame.com:houtai/oa.git

