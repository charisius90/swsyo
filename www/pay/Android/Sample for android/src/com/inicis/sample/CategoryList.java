package com.inicis.sample;

import java.util.ArrayList;
import java.util.List;


import android.app.ListActivity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

public class CategoryList extends ListActivity {
	
	private static final List<Row> rowList = new ArrayList();
	
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		
		
		
		if( rowList.size() == 0 )
		{	
			Bitmap  icon_1 = BitmapFactory.decodeResource(getResources(), R.drawable.category_icon);
			rowList.add( new Row("App Call Sample",icon_1,activityIntent(AppCallSample.class)));
		}
		
		EfficientAdapter adapter = new EfficientAdapter( this , rowList );
        
		setListAdapter(adapter);
		
        getListView().setTextFilterEnabled(true);
        getListView().setBackgroundColor(Color.WHITE);
        
       
	}
	
	private class Row {
		
		private String text;
		private Bitmap icon;
		private Intent intent;
		
		Row( String text , Bitmap icon ,Intent intent){
			this.text = text;
			this.icon = icon;
			this.intent = intent;
		}
		
		public String getText() {
			return text;
		}
		
		public Bitmap getIcon() {
			return icon;
		}

		public Intent getIntent() {
			
			return intent;
		}
	}
	
	
	
	private static class EfficientAdapter extends BaseAdapter {
		
        private LayoutInflater mInflater;
        private List items;
        
        public EfficientAdapter(Context context,List items) {
            // Cache the LayoutInflate to avoid asking for a new one each time.
            mInflater = LayoutInflater.from(context);
            this.items = items;
        }

        /**
         * The number of items in the list is determined by the number of speeches
         * in our array.
         *
         * @see android.widget.ListAdapter#getCount()
         */
        public int getCount() {
            return items.size();
        }

        /**
         * Since the data comes from an array, just returning the index is
         * sufficent to get at the data. If we were using a more complex data
         * structure, we would return whatever object represents one row in the
         * list.
         *
         * @see android.widget.ListAdapter#getItem(int)
         */
        public Object getItem(int position) {
            return position;
        }

        /**
         * Use the array index as a unique id.
         *
         * @see android.widget.ListAdapter#getItemId(int)
         */
        public long getItemId(int position) {
            return position;
        }

        /**
         * Make a view to hold each row.
         *
         * @see android.widget.ListAdapter#getView(int, android.view.View,
         *      android.view.ViewGroup)
         */
        public View getView(int position, View convertView, ViewGroup parent) {
            // A ViewHolder keeps references to children views to avoid unneccessary calls
            // to findViewById() on each row.
            ViewHolder holder;

            // When convertView is not null, we can reuse it directly, there is no need
            // to reinflate it. We only inflate a new View when the convertView supplied
            // by ListView is null.
            if (convertView == null) {
                convertView = mInflater.inflate(R.layout.list_item_icon_text, null);

                // Creates a ViewHolder and store references to the two children views
                // we want to bind data to.
                holder = new ViewHolder();
                holder.text = (TextView) convertView.findViewById(R.id.text);
                holder.icon = (ImageView) convertView.findViewById(R.id.icon);

                convertView.setTag(holder);
            } else {
                // Get the ViewHolder back to get fast access to the TextView
                // and the ImageView.
                holder = (ViewHolder) convertView.getTag();
            }
            
            Row ii = (Row)items.get( position );
            // Bind the data efficiently with the holder.
            holder.text.setTextColor(Color.BLACK);
            
            holder.text.setText( ii.getText() );
            
            Bitmap bm = ii.getIcon();
            if( bm != null )
            	holder.icon.setImageBitmap(bm);
           

            return convertView;
        }

        static class ViewHolder {
            TextView text;
            ImageView icon;
        }
    }
	
	protected Intent activityIntent(Class classs) {
		
        Intent result = new Intent();
        result.setClass(this,classs);
        
        return result;
    }
	
	@Override
	protected void onResume() {
		super.onResume();
	}
	
	@Override
	protected void onPause() {
		super.onPause();
	}
	
	@Override
	protected void onListItemClick(ListView l, View v, int position, long id) {
		
		Row row = rowList.get(position);
		Intent intent = row.getIntent();
		startActivity(intent);
	}
	
	@Override
	protected void onDestroy() {
				
		super.onDestroy();
		
	}
	
}
