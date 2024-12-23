import { Link, usePage } from "@inertiajs/react";
import React, { useState } from "react";

const Navbar = () => {
  const user = usePage().props.auth.user;
  const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);
  return (
    <div className="navbar bg-base-100 shadow-md">
      {/* --- Left Section: Logo and Links --- */}
      <div className="flex-1">
        {/* Logo */}
        <a className="btn btn-ghost normal-case text-xl">Shop</a>

        {/* Links (visible on larger screens) */}
        <div className="hidden lg:flex">
          <ul className="menu menu-horizontal px-1">
            <li>
              <a href="/dashboard">Dashboard</a>
            </li>
            <li>
              <a href="/profile">Profile</a>
            </li>
          </ul>
        </div>
      </div>

      {/* --- Right Section: Cart and User Authentication --- */}
      <div className="flex-none space-x-4">
        {/* Cart Dropdown (always visible) */}
        <div className="dropdown dropdown-end">
          <label tabIndex={0} className="btn btn-ghost btn-circle">
            <div className="indicator">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                className="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
              <span className="badge badge-sm indicator-item">8</span>
            </div>
          </label>
          {/* Cart Details */}
          <div
            tabIndex={0}
            className="dropdown-content z-[1] card card-compact bg-base-100 w-52 shadow"
          >
            <div className="card-body">
              <span className="text-lg font-bold">8 Items</span>
              <span className="text-info">Subtotal: $999</span>
              <div className="card-actions">
                <button className="btn btn-primary btn-block">View Cart</button>
              </div>
            </div>
          </div>
        </div>

        {/* User Section */}
        {user ? (
          <div className="dropdown dropdown-end">
            <label tabIndex={0} className="btn btn-ghost btn-circle avatar">
              <div className="w-10 rounded-full">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                  alt="User Avatar"
                />
              </div>
            </label>
            {/* User Dropdown */}
            <ul
              tabIndex={0}
              className="dropdown-content menu menu-sm bg-base-100 rounded-box z-[1] w-52 p-2 shadow"
            >
              <li>
                <Link href={route('profile.edit')} >
                  Profile

                </Link>
              </li>
             
              <li>
                <Link href={route('logout')} method="post" as="button">Logout</Link>
              </li>
            </ul>
          </div>
        ) : (
          // If user is not logged in, show Login and Sign Up buttons
          <div className="flex space-x-2">
            <Link href={route('login')} className="btn btn-primary">
              Login
            </Link>
            <Link href={route('register')} className="btn btn-secondary">
              Sign Up
            </Link>
          </div>
        )}
      </div>

      {/* --- Mobile Menu Button --- */}
      <div className="lg:hidden">
        <button
          onClick={() => setShowingNavigationDropdown(!showingNavigationDropdown)}
          className="btn btn-ghost"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            {/* Hamburger Menu Icon */}
            <path
              className={showingNavigationDropdown ? "hidden" : "block"}
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
            {/* Close Icon */}
            <path
              className={showingNavigationDropdown ? "block" : "hidden"}
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      {/* --- Mobile Dropdown Links --- */}
      {showingNavigationDropdown && (
        <div className="lg:hidden">
          <ul className="menu bg-base-100 p-4 shadow-md">
            <li>
              <a href="/dashboard">Dashboard</a>
            </li>
            <li>
              <a href="/profile">Profile</a>
            </li>
          </ul>
        </div>
      )}
    </div>
  );
};

export default Navbar;
