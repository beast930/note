//使用tortoisegit完成合并冲突的工作
1.将已修改的commit到本地
2.从远程仓库pull下来 提示冲突-->点击解决-->点击确定  //产生合并的冲突文件
3.对冲突文件进行修改 commit本地
此时 一个解决了的冲突文件已经放在本地仓库了

//解决git clone 过慢
git config --global http.https://github.com.proxy socks5://127.0.0.1:1080
git config --global https.https://github.com.proxy socks5://127.0.0.1:1080
//解除
git config --global --unset http.proxy
git config --global --unset https.proxy
