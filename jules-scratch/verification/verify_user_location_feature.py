from playwright.sync_api import sync_playwright, Page, expect
import os

def run(playwright):
    # Get the absolute path to the HTML files
    # The current working directory is the root of the repository
    project_root = os.getcwd()
    login_page_path = f"file://{project_root}/login.php"
    user_page_path = f"file://{project_root}/user.php"

    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Log in
    page.goto(login_page_path)
    page.get_by_placeholder("Username").fill("admin")
    page.get_by_placeholder("Password").fill("admin")
    page.get_by_role("button", name="Login").click()

    # It seems the login is via an ajax call and then redirects.
    # Playwright's file:// protocol doesn't support the POST request for login.
    # A real server would be needed to test the login flow.
    # For verification purposes, I will assume login is successful and navigate directly to user.php
    # In a real environment, I would start the server and use http://localhost.

    # Navigate to the user page
    page.goto(user_page_path)

    # Open the "Add User" modal
    page.get_by_role("button", name="ADD").click()

    # Wait for the modal to be visible
    expect(page.locator("#add_data_Modal")).to_be_visible()

    # Select the "Employer" role
    page.get_by_label("Role").select_option("employer")

    # Wait for the location dropdown to be visible
    expect(page.get_by_label("Location")).to_be_visible()

    # Select "LUZON"
    page.get_by_label("Location").select_option(label="LUZON")

    # Wait for the branch dropdown to be populated
    # We can wait for a specific option to appear
    expect(page.locator("#branch > option[value='1']")).to_have_text("Cavite")

    # Select "Cavite"
    page.get_by_label("Branch").select_option(label="Cavite")

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)