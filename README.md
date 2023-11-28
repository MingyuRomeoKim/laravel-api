## Tistory API

티스토리 OPEN API를 활용하여 지정한 장소에 원하는 데이터를 Json형식의 File로 저장할 수 있다.

### 초기 Request  URL

> https://www.tistory.com/oauth/authorize?client_id={your-client-id}&redirect_uri={your-callback-url}&response_type=code&state=mg-laravel

### .env 설정

```dotenv
MG_API_TISTORY_CLIENT_ID="your-tistory-app-id"
MG_API_TISTORY_CLIENT_SECRET="your-tistory-secret-key"
MG_API_TISTORY_REDIRECT_URL="your-tistory-callback-url"
MG_API_TISTORY_GRANT_TYPE="authorization_code"
MG_API_TISTORY_OUTPUT_TYPE="json"
MG_API_TISTORY_DATA_PATH="/Users/mingyukim/Desktop/tisyory/mg-next/src/data/"
```

MG_API_TISTORY_DATA_PATH 부분에 원하는 디렉토리를 지정하면 해당 위치에 Tistory 블로그 관련 데이터들이 Json File로 저장된다.

## Naver API

네이버 Search API를 활용하여 지정한 장소에 keyword에 해당하는 백과사전 정보 데이터를 Json형식의 File로 저장할 수 있다.

### 요청 예제

> http://127.0.0.1:8000/api/v1/naver/구조체

### 응답 예제

```json
{
    "lastBuildDate": "Mon, 27 Nov 2023 20:33:33 +0900",
    "total": 1094,
    "start": 1,
    "display": 10,
    "items": [
        {
            "title": "\ud504\ub85c\uadf8\ub798\ubc0d \uc5b8\uc5b4 \uad6c\uc870\uc801 \uc790\ub8cc\ud615",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=2270406&cid=51173&categoryId=51173",
            "description": "\uc5ec\ub7ec \uc790\ub8cc\ub97c \ubb36\uc5b4\uc11c \ud558\ub098\uc758 \ub2e8\uc704\ub85c \ucc98\ub9ac\ud558\ub294 \uc790\ub8cc\ud615\uc744 \uad6c\uc870\uc801 \uc790\ub8cc\ud615(structured data type)\uc774\ub77c \ud558\ub294\ub370, \ubc30\uc5f4\uacfc \ub808\ucf54\ub4dc\ub85c \uad6c\ubd84\ud560 \uc218 \uc788\ub2e4. \ubc30\uc5f4\uc740 \uc9d1\ud569\uccb4\uc5d0\uc11c\uc758 \uc704\uce58\ub85c \uc6d0\uc18c\ub97c \uc2dd\ubcc4\ud558\ub294 \ub3d9\uc9c8\ud615 \uc790\ub8cc\uc758... ",
            "thumbnail": "http:\/\/openapi-dbscthumb.phinf.naver.net\/3523_000_1\/20141020113155523_UQQBSZY17.jpg\/ka7_96_i1.jpg?type=m160_160"
        },
        {
            "title": "<b>\uad6c\uc870\uccb4<\/b>",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=6057449&cid=67350&categoryId=67350",
            "description": "\uac74\ucd95\ubb3c \ubc0f \uacf5\uc791\ubb3c\uc5d0 \uc791\uc6a9\ud558\ub294 \uac01\uc885 \ud558\uc911\uc5d0 \ub300\ud558\uc5ec \uadf8 \uac74\ucd95\ubb3c \ubc0f \uacf5\uc791\ubb3c\uc744 \uc548\uc804\ud558\uac8c \uc9c0\uc9c0\ud558\ub294 \uad6c\uc870\ubb3c\uc758 \ubf08\ub300 \uc790\uccb4\ub97c \ub9d0\ud558\uba70, \uc77c\ubc18\uc801\uc73c\ub85c \ubd80\ucc28<b>\uad6c\uc870\uccb4<\/b>\ub97c \uc81c\uc678\ud55c \uae30\ubcf8\ubf08\ub300\ub9cc\uc744 \ub9d0\ud55c\ub2e4. \uad6c\uccb4 \ucc38\uc870. \ucc38\uace0... ",
            "thumbnail": ""
        },
        {
            "title": "<b>\uad6c\uc870\uccb4<\/b>",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=390655&cid=50348&categoryId=50348",
            "description": "\ubaa8\ub4e0 \ubd80\ubd84\uc774 \uc0c1\ud638\uac04\uc5d0 \uade0\ub4f1\ud788 \uc720\uc9c0\ub418\uace0 \ud3c9\uade0\uc0c1\ud0dc\uc5d0 \uc788\ub3c4\ub85d \uacc4\ud68d\ub41c \uad6c\uc131.",
            "thumbnail": ""
        },
        {
            "title": "\ud074\ub798\uc2a4",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=3532993&cid=58528&categoryId=58528",
            "description": "[\ud45c 6-2 \ud074\ub798\uc2a4\uc640 \uac1d\uccb4\uc758 \uc608] || \ud074\ub798\uc2a4 || \uac1d\uccb4 || | \uc2b9\uc6a9\ucc28 | \uc18c\ub098\ud0c0, \uadf8\ub79c\uc838, SM5 | | \uc790\ub3d9\ucc28 | \uc2b9\uc6a9\ucc28, \ubc84\uc2a4, \ud2b8\ub7ed | | \uc6b4\uc1a1 \uc218\ub2e8 | \uc790\ub3d9\ucc28, \ubc30, \ube44\ud589\uae30 | \ud074\ub798\uc2a4\uc640 \ube44\uc2b7\ud55c \uac1c\ub150\uc744 C \uc5b8\uc5b4\uc5d0\uc11c \ucc3e\uc73c\uba74 <b>\uad6c\uc870\uccb4<\/b>(struct)\ub97c... ",
            "thumbnail": "http:\/\/openapi-dbscthumb.phinf.naver.net\/4666_000_1\/20161005133744978_2DZRPSFG3.jpg\/ka37_163_i1.jpg?type=m160_160"
        },
        {
            "title": "<b>\uad6c\uc870\uccb4<\/b>\uc2dd",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=838428&cid=50376&categoryId=50376",
            "description": "PL\/ I \uc5b8\uc5b4\uc5d0 \uc788\uc5b4\uc11c \uacc4\uc0b0 \uacb0\uacfc\uac00 <b>\uad6c\uc870\uccb4<\/b>\ub85c \ub418\ub294 \uc2dd.",
            "thumbnail": ""
        },
        {
            "title": "<b>\uad6c\uc870\uccb4<\/b> \ub300\uc785",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=838409&cid=50376&categoryId=50376",
            "description": "PL\/I \uc5b8\uc5b4\uc5d0 \uc788\uc5b4\uc11c \ub300\uc785\ubb38 \uc911, \uc88c\ubcc0\uc774 <b>\uad6c\uc870\uccb4<\/b> \ubcc0\uc218 \ud639\uc740 \uc758\uc0ac \ubcc0\uc218\uc774\uba70, \uc6b0\ubcc0\uc774 <b>\uad6c\uc870\uccb4<\/b>\uc2dd \ud639\uc740 \uc2a4\uce7c\ub77c\uc2dd\uc778 \uac83.",
            "thumbnail": ""
        },
        {
            "title": "<b>\uad6c\uc870\uccb4<\/b> \ubcc0\uc218",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=838430&cid=50376&categoryId=50376",
            "description": "PL\/I \uc5b8\uc5b4\uc5d0\uc11c \uc8fc<b>\uad6c\uc870\uccb4<\/b>\uba85\uacfc \uc138\ub85c <b>\uad6c\uc870\uccb4<\/b>\uba85.",
            "thumbnail": ""
        },
        {
            "title": "<b>\uad6c\uc870\uccb4<\/b>\uc758 \ubc30\uc5f4",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=846667&cid=50371&categoryId=50371",
            "description": "\ub3d9\uc77c\ud55c <b>\uad6c\uc870\uccb4<\/b>\uc758 \uc21c\uc11c\uac00 \ubd80\uc5ec\ub41c \uc9d1\ud569\uccb4, \uc989 <b>\uad6c\uc870\uccb4<\/b> \uc790\uccb4\uac00 \ubc30\uc5f4\uc758 \ud615\ud0dc\ub97c \uac16\ucd94\uace0 \uc788\ub294 \uac83. <b>\uad6c\uc870\uccb4<\/b>\uc758 \ubc30\uc5f4\uc740 <b>\uad6c\uc870\uccb4<\/b>\uc758 \uc774\ub984\uc5d0 \ucc28\uc6d0 \uc18d\uc131(\u6b21\u5143\u5c6c\u6027)\uc744 \uac16\uac8c \ud558\ub294 \uac83\uc774\ub2e4.",
            "thumbnail": ""
        },
        {
            "title": "\uc2a4\ud154\uc2a4 \uba54\ud0c0<b>\uad6c\uc870\uccb4<\/b> \uc81c\uc791 \uacfc\uc815",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=6504923&cid=51648&categoryId=63595",
            "description": "[\uc2a4\ud154\uc2a4 \uba54\ud0c0<b>\uad6c\uc870\uccb4<\/b> \uc81c\uc791 \uacfc\uc815] \uc801\uc678\uc120 \uce74\uba54\ub77c\uc5d0 \uac10\uc9c0\ub418\uc9c0 \uc54a\ub294 \uc2a4\ud154\uc2a4 \uae30\ub2a5\uc744 \uba54\ud0c0\uad6c\ucd08\uc81c\ub85c \uad6c\ud604\ud560 \uc218 \uc788\ub2e4\uace0 \ud55c\ub2e4. \uadf8\ub807\ub2e4\uba74 \uc774 \uc2a4\ud154\uc2a4 \uba54\ud0c0<b>\uad6c\uc870\uccb4<\/b>\ub294 \uc5b4\ub5bb\uac8c \ub9cc\ub4e4\uc5b4\uc9c0\ub294\uc9c0 \uc54c\uc544\ubcf8\ub2e4. \u25b6full\uc601\uc0c1... ",
            "thumbnail": "https:\/\/phinf.pstatic.net\/tvcast\/20210914_66\/lWecS_1631606822125QsVKp_JPEG\/1cbac82e-141e-11ec-9845-505dacfbaa5c_10.jpg#1920x1080#m"
        },
        {
            "title": "\uc2e0\ud638\ub97c \uc99d\ud3ed\uc2dc\ud0a4\ub294 \uba54\ud0c0<b>\uad6c\uc870\uccb4<\/b>",
            "link": "https:\/\/terms.naver.com\/entry.naver?docId=6504637&cid=51648&categoryId=63595",
            "description": "[\uc2e0\ud638\ub97c \uc99d\ud3ed\uc2dc\ud0a4\ub294 \uba54\ud0c0<b>\uad6c\uc870\uccb4<\/b>] 3D\ud504\ub9b0\ud130\ub97c \uc774\uc6a9\ud574 \ucd08\uc74c\ud30c \uc2e0\ud638\ub97c \uc99d\ud3ed\uc2dc\ud0a4\ub294 \uba54\ud0c0<b>\uad6c\uc870\uccb4<\/b>\ub97c \ub9cc\ub4e0 \uc774\ud559\uc8fc \ub2e8\uc7a5 \uc5f0\uad6c\ud300. \ucd08\uc74c\ud30c \uc2e0\ud638\ub97c \uc99d\ud3ed\ud558\uba74 \uc2e0\ud638\ub97c \uba40\ub9ac \ubcf4\ub0bc \uc218 \uc788\uace0 \ub354 \uc815\ud655\ud558\uac8c \uc2e0\ud638\ub97c \uc7b4 \uc218... ",
            "thumbnail": "https:\/\/phinf.pstatic.net\/tvcast\/20210908_283\/UmpXR_1631056031777Nrf7L_JPEG\/4e958861-1030-11ec-89ce-48df379ccacc_03.jpg#1920x1080#m"
        }
    ]
}
```

### .env 설정

```dotenv
MG_API_NAVER_CLIENT_ID="your-naver-client-id"
MG_API_NAVER_CLIENT_SECRET="your-naver-client-secret-key"
MG_API_NAVER_DATA_PATH="/Users/mingyukim/Desktop/tisyory/wordgame/src/data/"
```

MG_API_TISTORY_DATA_PATH 부분에 원하는 디렉토리를 지정하면 해당 위치에 Naver Search API 관련 데이터들이 Json File로 저장된다.

## 설치 및 실행 방법

### 프로젝트 설치

> git clone https://github.com/MingyuRomeoKim/laravel-api.git

### artisan 명령어를 이용한 실행

> php artisan serve
