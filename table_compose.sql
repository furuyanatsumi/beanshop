--データベース："beanshop"

-----------------テーブル構造-----------------

--userテーブル
'userid' int(11) NOT NULL AUTO_INCREMENT PRIMARYKEY,
'username' varchar(20) NOT NULL,
'password' varchar(20) NOT NULL,
'type' int(11),
'create_datetime' timestamp NOT NULL CURRENT_TIMESTAMP

--cartテーブル
'cartid' int(11) NOT NULL AUTO_INCREMENT PRIMARYKEY,
'userid' int(11) NOT NULL,
'itemid' int(11) NOT NULL,
'amount' int(11) NOT NULL,
'roast' varchar(20) NOT NULL,
'create_datetime' timestamp NOT NULL CURRENT_TIMESTAMP,
'update_datetime' timestamp CURRENT_TIMESTAMP

--historyテーブル
'historyid' int(11) NOT NULL AUTO_INCREMENT PRIMARYKEY,
'userid' int(11) NOT NULL,
'itemid' int(11) NOT NULL,
'amount' int(11) NOT NULL,
'roast' varchar(20) NOT NULL,
'note' varchar(400) ,
'create_datetime' timestamp NOT NULL CURRENT_TIMESTAMP

--itemsテーブル
'itemid' int(11) NOT NULL AUTO_INCREMENT PRIMARYKEY,
'itemname' varchar(20) NOT NULL,
'price' int(11) NOT NULL,
'img' varchar(100) NOT NULL,
'status' int(11) NOT NULL,
'stock' int(11) NOT NULL,
'comment' varchar(100) NOT NULL,
'create_datetime' timestamp NOT NULL CURRENT_TIMESTAMP,
'update_datetime' timestamp CURRENT_TIMESTAMP

--item_infoテーブル
'itemid' int(11) NOT NULL AUTO_INCREMENT PRIMARYKEY,
'roast' varchar(20),
'body' varchar(20),
'acidity' varchar(20),
'bitterness' varchar(20),
'create_datetime' timestamp NOT NULL CURRENT_TIMESTAMP
