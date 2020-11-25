// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
var firebaseConfig = {
    apiKey: "AIzaSyBvLr2Ero4lbSY8fQs5Y2N5-2MJYjNv170",
    authDomain: "s192140auth.firebaseapp.com",
    databaseURL: "https://s192140auth.firebaseio.com",
    projectId: "s192140auth",
    storageBucket: "s192140auth.appspot.com",
    messagingSenderId: "802161843454",
    appId: "1:802161843454:web:40b77ab378cd043d84d9af",
    measurementId: "G-518T4ZTRED"
};
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();


//----------------------------------------------
// ドメインとポート番号
//----------------------------------------------
let domain = document.domain;
let port   = (domain === 'localhost')?  5000:80;