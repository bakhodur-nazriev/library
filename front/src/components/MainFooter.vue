<script setup>
import FacebookIcon from "./icons/FacebookIcon.vue";
import TwitterIcon from "./icons/TwitterIcon.vue";
import YoutubeIcon from "./icons/YoutubeIcon.vue";
import InstagramIcon from "./icons/InstagramIcon.vue";

const currentYear = new Date().getFullYear();
</script>

<template>
  <footer>
    <div class="footer-details">
      <div class="left-side">
        <router-link :to="`/${$i18n.locale}/`" class="footer-logo">
          <img src="../assets/main-logo-1.png" alt="msi-main-logo"/>
        </router-link>

        <p class="left-side_title">{{ $t('footer.title') }}</p>

        <div class="social-media_block">
          <a href="https://www.facebook.com/groups/348373586485522/?ref=share" target="_blank">
            <FacebookIcon/>
          </a>
          <a href="https://t.me/MedicalSocialInstitute" target="_blank">
            <TwitterIcon/>
          </a>
          <a href="https://www.youtube.com/channel/UCcksk0evZa-OYaSWQu45mZA" target="_blank">
            <YoutubeIcon/>
          </a>
          <a href="https://www.instagram.com/msi.tj/" target="_blank">
            <InstagramIcon/>
          </a>
        </div>
      </div>

      <div class="right-side">
        <h4>{{ $t('label.contact_us') }}</h4>
        <p>{{ $t('label.tel') }}: (+992) 918 67 74 48</p>
        <p>{{ $t('label.fax') }}: (+992) 918 67 74 48</p>
      </div>
    </div>
    <div class="copyright-block">
      <p>{{ $t('copyrights') }}
        <span>{{ currentYear }} {{ $t('label.year') }}.</span>
      </p>
    </div>
  </footer>
</template>

<style scoped lang="scss">
footer {
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  background-color: #333;
  color: var(--color-white);

  .copyright-block {
    display: flex;
    justify-content: center;
    background-color: #242424;
    width: 100%;

    p {
      font-size: 14px;
    }
  }

  .footer-details {
    display: flex;
    justify-content: space-around;
    align-items: center;
    gap: 40px;
    padding: 20px;
    width: 1280px;
  }

  .left-side {
    max-width: 400px;

    &_title {
      font-size: 14px;
      line-height: 1.4;
    }

    .footer-logo {
      img {
        width: 200px;
      }
    }

    .social-media_block {
      display: flex;
      gap: 5px;

      a {
        display: flex;
        align-items: center;
        transition: all 0.1s ease-in-out;

        &:hover {
          opacity: 0.5;
        }
      }
    }
  }

  .right-side {
    max-width: 400px;

    h4 {
      margin-top: 0;
      font-size: 24px;
      font-weight: normal;
      margin-bottom: 15px;
    }

    p {
      font-size: 14px;
      margin: 0 0 10px;
    }
  }
}
</style>