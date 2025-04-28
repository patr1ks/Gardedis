<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <nav class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50">
      <div class="flex flex-wrap justify-between items-center">
        <div class="flex justify-start items-center">
          <button
            data-drawer-target="drawer-navigation"
            data-drawer-toggle="drawer-navigation"
            aria-controls="drawer-navigation"
            class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          >
            <svg
              aria-hidden="true"
              class="w-6 h-6"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"
              ></path>
            </svg>
            <svg
              aria-hidden="true"
              class="hidden w-6 h-6"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              ></path>
            </svg>
            <span class="sr-only">Toggle sidebar</span>
          </button>
          <Link :href="route('admin.dashboard')" class="flex items-center justify-between mr-4">
            <svg height="30px" viewBox="0 0 512 512" width="30px" xmlns="http://www.w3.org/2000/svg"><title/><path d="M357.57,223.94a79.48,79.48,0,0,0,56.58-23.44l77-76.95c6.09-6.09,6.65-16,.85-22.39a16,16,0,0,0-23.17-.56L400.2,169.18a12.29,12.29,0,0,1-17.37,0c-4.79-4.78-4.53-12.86.25-17.64l68.33-68.33a16,16,0,0,0-.56-23.16A15.62,15.62,0,0,0,440.27,56a16.71,16.71,0,0,0-11.81,4.9l-68.27,68.26a12.29,12.29,0,0,1-17.37,0c-4.78-4.78-4.53-12.86.25-17.64L411.4,43.21a16,16,0,0,0-.56-23.16A15.62,15.62,0,0,0,400.26,16a16.73,16.73,0,0,0-11.81,4.9L311.5,97.85a79.49,79.49,0,0,0-23.44,56.59v8.23A16,16,0,0,1,283.37,174l-35.61,35.62a4,4,0,0,1-5.66,0L68.82,36.33a16,16,0,0,0-22.58-.06C31.09,51.28,23,72.47,23,97.54c-.1,41.4,21.66,89,56.79,124.08l85.45,85.45A64.79,64.79,0,0,0,211,326a64,64,0,0,0,16.21-2.08,16.24,16.24,0,0,1,4.07-.53,15.93,15.93,0,0,1,10.83,4.25l11.39,10.52a16.12,16.12,0,0,1,4.6,11.23v5.54a47.73,47.73,0,0,0,13.77,33.65l90.05,91.57.09.1a53.29,53.29,0,0,0,75.36-75.37L302.39,269.9a4,4,0,0,1,0-5.66L338,228.63a16,16,0,0,1,11.32-4.69Z"/><path d="M211,358a97.32,97.32,0,0,1-68.36-28.25l-13.86-13.86a8,8,0,0,0-11.3,0l-85,84.56c-15.15,15.15-20.56,37.45-13.06,59.29a30.63,30.63,0,0,0,1.49,3.6C31,484,50.58,496,72,496a55.68,55.68,0,0,0,39.64-16.44L225,365.66a4.69,4.69,0,0,0,1.32-3.72l0-.26a4.63,4.63,0,0,0-5.15-4.27A97.09,97.09,0,0,1,211,358Z"/></svg>
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white pl-3">Gardēdis</span>
          </Link>
        </div>
        <div class="flex items-center lg:order-2">
          <!-- Dropdown menu -->
          <div
            class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:divide-gray-600 dark:bg-gray-700"
            id="notification-dropdown"
          >
            <div>
              <a
                href="#"
                class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600"
              >
                <div class="flex-shrink-0">
                  <img
                    class="w-11 h-11 rounded-full"
                    src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png"
                    alt="Bonnie Green avatar"
                  />
                  <div
                    class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 rounded-full border border-white bg-primary-700 dark:border-gray-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="w-3 h-3 text-white"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"
                      ></path>
                      <path
                        d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="pl-3 w-full">
                  <div
                    class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"
                  >
                    New message from
                    <span class="font-semibold text-gray-900 dark:text-white"
                      >Bonnie Green</span
                    >: "Hey, what's up? All set for the presentation?"
                  </div>
                  <div
                    class="text-xs font-medium text-primary-600 dark:text-primary-500"
                  >
                    a few moments ago
                  </div>
                </div>
              </a>
              <a
                href="#"
                class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600"
              >
                <div class="flex-shrink-0">
                  <img
                    class="w-11 h-11 rounded-full"
                    src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png"
                    alt="Jese Leos avatar"
                  />
                  <div
                    class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-gray-900 rounded-full border border-white dark:border-gray-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="w-3 h-3 text-white"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="pl-3 w-full">
                  <div
                    class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"
                  >
                    <span class="font-semibold text-gray-900 dark:text-white"
                      >Jese leos</span
                    >
                    and
                    <span class="font-medium text-gray-900 dark:text-white"
                      >5 others</span
                    >
                    started following you.
                  </div>
                  <div
                    class="text-xs font-medium text-primary-600 dark:text-primary-500"
                  >
                    10 minutes ago
                  </div>
                </div>
              </a>
              <a
                href="#"
                class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600"
              >
                <div class="flex-shrink-0">
                  <img
                    class="w-11 h-11 rounded-full"
                    src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/joseph-mcfall.png"
                    alt="Joseph McFall avatar"
                  />
                  <div
                    class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-red-600 rounded-full border border-white dark:border-gray-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="w-3 h-3 text-white"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="pl-3 w-full">
                  <div
                    class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"
                  >
                    <span class="font-semibold text-gray-900 dark:text-white"
                      >Joseph Mcfall</span
                    >
                    and
                    <span class="font-medium text-gray-900 dark:text-white"
                      >141 others</span
                    >
                    love your story. See it and view more stories.
                  </div>
                  <div
                    class="text-xs font-medium text-primary-600 dark:text-primary-500"
                  >
                    44 minutes ago
                  </div>
                </div>
              </a>
              <a
                href="#"
                class="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600"
              >
                <div class="flex-shrink-0">
                  <img
                    class="w-11 h-11 rounded-full"
                    src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/roberta-casas.png"
                    alt="Roberta Casas image"
                  />
                  <div
                    class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-green-400 rounded-full border border-white dark:border-gray-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="w-3 h-3 text-white"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="pl-3 w-full">
                  <div
                    class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"
                  >
                    <span class="font-semibold text-gray-900 dark:text-white"
                      >Leslie Livingston</span
                    >
                    mentioned you in a comment:
                    <span
                      class="font-medium text-primary-600 dark:text-primary-500"
                      >@bonnie.green</span
                    >
                    what do you say?
                  </div>
                  <div
                    class="text-xs font-medium text-primary-600 dark:text-primary-500"
                  >
                    1 hour ago
                  </div>
                </div>
              </a>
              <a
                href="#"
                class="flex py-3 px-4 hover:bg-gray-100 dark:hover:bg-gray-600"
              >
                <div class="flex-shrink-0">
                  <img
                    class="w-11 h-11 rounded-full"
                    src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/robert-brown.png"
                    alt="Robert image"
                  />
                  <div
                    class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-purple-500 rounded-full border border-white dark:border-gray-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="w-3 h-3 text-white"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="pl-3 w-full">
                  <div
                    class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400"
                  >
                    <span class="font-semibold text-gray-900 dark:text-white"
                      >Robert Brown</span
                    >
                    posted a new video: Glassmorphism - learn how to implement
                    the new design trend.
                  </div>
                  <div
                    class="text-xs font-medium text-primary-600 dark:text-primary-500"
                  >
                    3 hours ago
                  </div>
                </div>
              </a>
            </div>
            <a
              href="#"
              class="block py-2 text-md font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-600 dark:text-white dark:hover:underline"
            >
              <div class="inline-flex items-center">
                <svg
                  aria-hidden="true"
                  class="mr-2 w-4 h-4 text-gray-500 dark:text-gray-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                  <path
                    fill-rule="evenodd"
                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
                View all
              </div>
            </a>
          </div>
          <!-- Dropdown menu -->
          <button
            type="button"
            class="flex mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button"
            aria-expanded="false"
            data-dropdown-toggle="dropdown"
          >
            <span class="sr-only">Open user menu</span>
            <img
              class="w-8 h-8 rounded-full"
              src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/michael-gough.png"
              alt="user photo"
            />
          </button>
          <!-- Dropdown menu -->
          <div
            class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
            id="dropdown"
          >
            <div class="py-3 px-4">
              <span
                class="block text-sm font-semibold text-gray-900 dark:text-white"
                >Neil Sims</span
              >
              <span
                class="block text-sm text-gray-900 truncate dark:text-white"
                >name@flowbite.com</span
              >
            </div>
            <ul
              class="py-1 text-gray-700 dark:text-gray-300"
              aria-labelledby="dropdown"
            >
              <li>
                <a
                  href="#"
                  class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white"
                  >My profile</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white"
                  >Account settings</a
                >
              </li>
            </ul>
            <ul
              class="py-1 text-gray-700 dark:text-gray-300"
              aria-labelledby="dropdown"
            >
              <li>
                <a
                  href="#"
                  class="flex items-center py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                  ><svg
                    class="mr-2 w-5 h-5 text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  My likes</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                  ><svg
                    class="mr-2 w-5 h-5 text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"
                    ></path>
                  </svg>
                  Collections</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="flex justify-between items-center py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                >
                  <span class="flex items-center">
                    <svg
                      aria-hidden="true"
                      class="mr-2 w-5 h-5 text-primary-600 dark:text-primary-500"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                    Pro version
                  </span>
                  <svg
                    aria-hidden="true"
                    class="w-5 h-5 text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </a>
              </li>
            </ul>
            <ul
              class="py-1 text-gray-700 dark:text-gray-300"
              aria-labelledby="dropdown"
            >
              <li>
                <Link :href="route('logout')" method="post" as="button"
                  class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                  >Sign out</Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
</template>