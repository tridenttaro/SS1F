// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
var firebaseConfig = {
  apiKey: "AIzaSyBTSiME_V-y0hTjQ-0-ybO52OafUuEKFzo",
  authDomain: "webapp-503ed.firebaseapp.com",
  databaseURL: "https://webapp-503ed.firebaseio.com",
  projectId: "webapp-503ed",
  storageBucket: "webapp-503ed.appspot.com",
  messagingSenderId: "888116032345",
  appId: "1:888116032345:web:faff257925dda64150d248",
  measurementId: "G-T9TEDPS1PF"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
firebase.analytics();

//----------------------------------------------
// ドメインとポート番号
//----------------------------------------------
let domain = document.domain;
let port   = (domain === 'localhost')?  5000:80;