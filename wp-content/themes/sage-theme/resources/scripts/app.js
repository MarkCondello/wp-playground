import {domReady} from '@roots/sage/client';
import axios from 'axios';

/**
 * app.main
 */
const main = async (err) => {
  if (err) {
    // handle hmr errors
    console.error(err);
  }

  const userVoteBtn = document.getElementById('user_vote'),
  voteCounter = document.getElementById('vote_counter');
  if(userVoteBtn && voteCounter) {
    userVoteBtn.addEventListener('click', function(ev) {
      ev.preventDefault()
      const ajaxUrl = site_script.ajaxurl,
      post_id = this.dataset.postId,
      nonce = this.dataset.nonce;

      let form_data = new FormData;
      form_data.append('action', 'user_vote');
      form_data.append('post_id', post_id);
      form_data.append('nonce', nonce);
      form_data.append('is_ajax', true);

      axios.post(ajaxUrl, form_data)
        .then(resp => {
          console.log({data: resp.data})
          voteCounter.innerHTML = resp.data.votes_count
        })
        .catch(err => {
          console.error();
        })
    });
  }

  // axios.get('/wp-json/wp/v2/pages', {
  //   search:'Archive',
  //   _fields: 'id, title, link',
  // })
  
  // axios.get('/wp-json/wp/v2/pages?search=Archive&_fields=id,title.link')
  // .then(resp => console.log({resp}))

  // axios.post('/wp-json/wp/v2/posts', 
  //   {
  //     'title': 'Post created from Axios',
  //     'content': 'Post content created from Axios',
  //     'status': 'publish'
  //   }, 
  //   {
  //     headers: {
  //       'Authorization': 'Basic base64encoded dXNlcjpwYXNzd29yZA==',
  //       'Content-Type': 'application/json'
  //     }
  //   })
  //   .then(resp => console.log("Post req: ", resp))
  //   .catch(err => console.error)


  // application code
};

/**
 * Initialize
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
domReady(main);
import.meta.webpackHot?.accept(main);
