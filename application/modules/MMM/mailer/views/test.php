/* Add your css styles here */

* {
        margin: 0;
        padding: 0;
    }
   
    .hide {
        display: none;
    }
   
   body {
       font-size: 100%;
       margin: 15px;      
       color: #fff;
   }
  
   .actionButons {
       width: 100px;
       height: 30px;
   }
  
  
  
   ul, li {
       list-style: none;
   }
  
   ul {
       overflow: hidden;
       padding: 15px;
   }
  
   ul li {
       margin: 15px;
       float: left;
       position: relative;
   }
  
   ul li div {
       text-decoration: none;
       color: #000;
       background: #ffc;
       display: block;
       height: 150px;
       width: 150px;
       padding: 10px;
       border-style: solid;
   }
  
   ul li div img {
       padding: 1px 3px;
       margin: 10px;
       position: absolute;
       top: 0;
       right: 0;
   }
  
   ul li textarea {
       font-family: 'Chewy', arial, sans-serif;
       background: rgba(0, 0, 0, 0); 
       resize: none;
       padding: 3px;
       border-style: none;
   }
  
   .note-title {
       font-size: 140%;
       font-weight: bold;
       height: 32px;
       width: 70%;
   }
  
   .note-content {
       font-size:120%;
       height: 100px;
       width: 95%;
   }
  
   
  ul li div:hover, ul li div:focus {
    position:relative;
    z-index:5;
  }
 
  ul li div.colour1 {
      background: #ffc;
  }
  ul li div.colour2 {
      background: #cfc;
  }
  ul li div.colour3 {
      background: #ccf;
  }
