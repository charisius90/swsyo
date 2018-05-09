package com.sample.launched;

import java.util.HashMap;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;

public class NewAppActivity extends Activity{
	
	private String TAG ="SAMPLE";
	
	
	protected void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.launched_content);
		
		Intent intent = getIntent();
		String data = intent.getDataString();
		
		// 예제에 넘어온 data변수의 내용은 
		// AppForSample://param1=test&param2=inicis 입니다. 
		// 이를 parameter 형식으로 잘라서 map에 넣습니다.
		
		HashMap mapData = getData(data.substring(15));
		
		TextView view = (TextView)findViewById(R.id.text_view);
		view.setText("어플리케이션에서 응답한 내용\n param1="+mapData.get("param1")+" , param2="+mapData.get("param2"));
	}
	
	private HashMap getData(String urlData){
		
		HashMap map = new HashMap();
		String []splitParam = urlData.split( "&" );
		
		for( int i = 0 ; i < splitParam.length ; i++ )
		{
			int idx = 0;
			int len = 0;
			String value;
			String name;
			
			if( splitParam[i] != null 
					&& (len = splitParam[i].length()) > 0 
							&& (idx = splitParam[i].indexOf("=")) > -1 )
			{
				
				name = splitParam[i].substring(0,idx).trim();
				value = (len == idx+1) ? "" : splitParam[i].substring(idx+1).trim();
				
				map.put(name,value);
			}
		}
		
		return map;
	}
}
