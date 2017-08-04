var KLChargeCard = {
    reader: false,
    init: function(){
        try{
            this.reader = new ActiveXObject("AccessCard.src.card.ClCard")
        }catch(e){
            //console.log('只有ie浏览器能使用读写卡程序！');
        }
    },
    //发卡
    fk: function(port,cardno){
        //return {"status": true,"info": 'success'};
        if(!this.reader){
            return {"status": false,"info": '无法驱动读写卡程序！'};
        }
        var s,ret,isOk,id,portNum;
        portNum = port;
        s = this.reader.InitReader( "MT318_219",portNum, 9600);
        id = cardno;

        ret = this.reader.issueCard(id);
        isOk = ret.isOk;
        if(isOk){
            return {"status": true,"info": ret.data};
        }else{
            return {"status": false,"info": ret.data};
        }   
    },
    //读卡
    readCard: function(port){
        //return {"status": true,"cardNo": '8888888888888881'};
        if(!this.reader){
            return {"status": false,"info": '无法驱动读写卡程序！'};
        }
        var s,ret,id,isOk,portNum;
        portNum = port;
        s = this.reader.InitReader( "MT318_219",portNum, 9600);
        ret = this.reader.ReadCardID();
        isOk = ret.isOk;
        if(isOk){
            id = ret.data;
            return {"status": true,"cardNo": id};
        }else{
            return {"status": false,"info": ret.data};
        }
    },
    //充值
    cz: function(port,cardno,money,stime){
        //return {"status": true,"info": 'success'};
        if(!this.reader){
            return {"status": false,"info": '无法驱动读写卡程序！'};
        }
        var s,ret,isOk,id,moneys,times,portNum;
        portNum = port;
        s = this.reader.InitReader( "MT318_219",portNum, 9600);
    
        id = cardno;
        moneys = money;
        times = stime;
        ret = this.reader.Increase(id,moneys * 100,times);
        isOk = ret.isOk;
        if(isOk){
            return {"status": true,"info": ret.data};
        }else{
            return {"status": false,"info": ret.data};
        }
    },
    //扣款
    kk: function(port,money){
        //return {"status": true,"info": 'success'};
        if(!this.reader){
            return {"status": false,"info": '无法驱动读写卡程序！'};
        }
        var s,ret,isOk,moneys,portNum;
        portNum = port;
        s = this.reader.InitReader( "MT318_219",portNum, 9600);
        moneys = money;
        ret = this.reader.decrease(moneys * 100);
        isOk = ret.isOk;
        if(isOk){
            return {"status": true,"info": ret.data};
        }else{
            alert(ret.data);
            return {"status": false,"info": ret.data};
        }
    },
    //余额
    ye: function(port){
        //return {"status": true,"money": 100};
        if(!this.reader){
            return {"status": false,"info": '无法驱动读写卡程序！'};
        }
        var s,ret,money,isOk,portNum;
        portNum = port;
        s = this.reader.InitReader( "MT318_219",portNum, 9600);
        
        ret = this.reader.ReadPurseFile();
        isOk = ret.isOk;
        if(isOk){
            money = ret.data;
            return {"status": true,"money": money / 100};
        }else{
            return {"status": false,"info": ret.data};
        }
    }
};
KLChargeCard.init();